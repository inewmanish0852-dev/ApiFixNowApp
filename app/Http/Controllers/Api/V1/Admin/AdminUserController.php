<?php
// AdminUserController.php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
     // AdminUserController
    public function index(Request $request)
    {
        $users = User::with('role','providerProfile')
            ->when($request->role,   fn($q) => $q->whereHas('role', fn($r) => $r->where('slug', $request->role)))
            ->when($request->search, fn($q) => $q->where('name','like','%'.$request->search.'%')
                                                ->orWhere('email','like','%'.$request->search.'%'))
            ->latest()
            ->paginate(20);

        return $this->paginated($users, 'Users fetched.');
    }

    public function show($id)
    {
        $user = User::with('role','providerProfile.skills')->find($id);
        if (! $user) return $this->notFound('User not found.');
        return $this->success($user);
    }

    public function toggleActive($id)
    {
        $user = User::find($id);
        if (! $user) return $this->notFound();
        $user->update(['is_active' => ! $user->is_active]);
        return $this->success(['is_active' => $user->is_active], 'Status updated.');
    }

    public function verify($id)
    {
        $user = User::find($id);
        if (! $user) return $this->notFound();
        $user->update(['is_verified' => true]);
        return $this->success(null, 'User verified successfully.');
    }
}