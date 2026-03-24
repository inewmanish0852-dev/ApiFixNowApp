<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ProviderProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Storage, Validator};
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponse;

class ProfileController extends Controller
{
    use ApiResponse;
    public function update(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name'    => 'sometimes|string|max:100',
            'phone'   => 'sometimes|string|max:15|unique:users,phone,'.auth()->id(),
            'city'    => 'sometimes|string',
            'state'   => 'sometimes|string',
            'address' => 'sometimes|string',
            'lat'     => 'sometimes|numeric',
            'lng'     => 'sometimes|numeric',
        ]);

        if ($v->fails()) return response()->json(['success' => false, 'errors' => $v->errors()], 422);

        auth('web')->user()->update($request->only('name','phone','city','state','address','lat','lng'));

        $user = User::with('role')->find(auth()->id());
        return $this->success($user, 'Profile updated successfully.');
    }

    public function updateProviderProfile(Request $request)
    {
        $v = Validator::make($request->all(), [
            'category'         => 'sometimes|string',
            'bio'              => 'sometimes|string|max:500',
            'experience_years' => 'sometimes|integer|min:0',
            'hourly_rate'      => 'sometimes|numeric|min:0',
        ]);

        if ($v->fails()) return response()->json(['success' => false, 'errors' => $v->errors()], 422);

        $profile = ProviderProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            $request->only('category','bio','experience_years','hourly_rate')
        );

        return $this->success($profile->load('skills'), 'Profile updated successfully.');
    }

    public function syncSkills(Request $request)
    {
        $request->validate(['skills' => 'required|array', 'skills.*' => 'string|max:60']);

        $profile = auth('web')->user();
        $profile->skills()->delete();

        foreach ($request->skills as $skill) {
            $profile->skills()->create(['skill_name' => $skill]);
        }

        return $this->success($profile->skills, 'Skills updated successfully.');
    }

    public function toggleAvailability()
    {
        $profile = auth('web')->user();

        $profile->update(['is_available' => ! $profile->is_available]);

        return $this->success([
            'is_available' => $profile->is_available,
        ], 'Availability updated successfully.');
    }
    public function earnings()
    {
        $profile = auth('web')->user();
        $earnings = \App\Models\Booking::where('provider_id', $profile->id)
            ->where('status', 'completed')
            ->selectRaw('
                COUNT(*) as total_jobs,
                SUM(amount) as total_earnings,
                SUM(CASE WHEN DATE(created_at) = CURDATE() THEN amount ELSE 0 END) as today,
                SUM(CASE WHEN MONTH(created_at) = MONTH(NOW()) THEN amount ELSE 0 END) as this_month
            ')
            ->first();

        return $this->success($earnings, 'Earnings fetched successfully.');
    }

    public function changePassword(Request $request)
    {
        $v = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        if ($v->fails()) return $this->validationError($v->errors());

        if (! Hash::check($request->current_password, auth('web')->user()->password)) {
            return $this->error('Current password is incorrect.', 422);
        }

        auth('web')->user()->update(['password' => Hash::make($request->password)]);

        return $this->success(null, 'Password changed successfully.');
    }

    public function uploadAvatar(Request $request)
    {
        $v = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($v->fails()) return $this->validationError($v->errors());

        $old = auth('web')->user()->avatar;
        if ($old && Storage::disk('public')->exists(str_replace('/storage/', '', $old))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $old));
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        auth('web')->user()->update(['avatar' => Storage::url($path)]);

        return $this->success(['avatar' => Storage::url($path)], 'Avatar updated.');
    }
}