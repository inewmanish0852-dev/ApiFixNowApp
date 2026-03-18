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
    // PUT /profile
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

        auth()->user()->update($request->only('name','phone','city','state','address','lat','lng'));

        return response()->json(['success' => true, 'data' => auth()->user()->load('role')]);
    }

    // POST /profile/avatar
    // public function uploadAvatar(Request $request)
    // {
    //     $request->validate(['avatar' => 'required|image|max:2048']);

    //     $path = $request->file('avatar')->store('avatars', 'public');

    //     auth()->user()->update(['avatar' => Storage::url($path)]);

    //     return response()->json(['success' => true, 'avatar' => Storage::url($path)]);
    // }

    // PUT /provider/profile
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

        return response()->json(['success' => true, 'data' => $profile->load('skills')]);
    }

    // POST /provider/profile/skills
    public function syncSkills(Request $request)
    {
        $request->validate(['skills' => 'required|array', 'skills.*' => 'string|max:60']);

        $profile = auth()->user();
        $profile->skills()->delete();

        foreach ($request->skills as $skill) {
            $profile->skills()->create(['skill_name' => $skill]);
        }

        return response()->json(['success' => true, 'data' => $profile->skills]);
    }

    // PATCH /provider/availability
    public function toggleAvailability()
    {
        $profile = auth()->user();

        $profile->update(['is_available' => ! $profile->is_available]);

        return response()->json([
            'success'      => true,
            'is_available' => $profile->is_available,
        ]);
    }

    // GET /provider/earnings
    public function earnings()
    {
        $profile = auth()->user();
        $earnings = \App\Models\Booking::where('provider_id', $profile->id)
            ->where('status', 'completed')
            ->selectRaw('
                COUNT(*) as total_jobs,
                SUM(amount) as total_earnings,
                SUM(CASE WHEN DATE(created_at) = CURDATE() THEN amount ELSE 0 END) as today,
                SUM(CASE WHEN MONTH(created_at) = MONTH(NOW()) THEN amount ELSE 0 END) as this_month
            ')
            ->first();

        return response()->json(['success' => true, 'data' => $earnings]);
    }

    // POST /profile/password
    public function changePassword(Request $request)
    {
        $v = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        if ($v->fails()) return $this->validationError($v->errors());

        if (! Hash::check($request->current_password, auth()->user()->password)) {
            return $this->error('Current password is incorrect.', 422);
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);

        return $this->success(null, 'Password changed successfully.');
    }

    // POST /profile/avatar
    public function uploadAvatar(Request $request)
    {
        $v = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($v->fails()) return $this->validationError($v->errors());

        $old = auth()->user()->avatar;
        if ($old && Storage::disk('public')->exists(str_replace('/storage/', '', $old))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $old));
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        auth()->user()->update(['avatar' => Storage::url($path)]);

        return $this->success(['avatar' => Storage::url($path)], 'Avatar updated.');
    }
}