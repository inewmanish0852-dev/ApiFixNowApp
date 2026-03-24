<?php
// =====================================================
// FILE: app/Http/Controllers/Admin/UserController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role_id', '2')->withCount('bookings')->latest();

        if ($request->filled('search'))
            $query->where(fn($q) =>
                $q->where('name',  'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%")
            );

        if ($request->filled('status')) {
            if ($request->status === 'banned') $query->where('is_banned', true);
            if ($request->status === 'active') $query->where('is_banned', false)->where('is_active', true);
        }

        $users = $query->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['bookings.service', 'bookings.provider.user', 'reviews']);
        return view('admin.users.show', compact('user'));
    }

    public function ban(Request $request, User $user)
    {
        $request->validate(['reason' => 'required|string']);
        $user->update(['is_banned' => true, 'ban_reason' => $request->reason, 'is_active' => false]);
        return back()->with('success', "User {$user->name} has been banned.");
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false, 'ban_reason' => null, 'is_active' => true]);
        return back()->with('success', "User {$user->name} has been unbanned.");
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}