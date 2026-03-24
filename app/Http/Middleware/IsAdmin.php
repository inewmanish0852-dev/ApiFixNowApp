<?php
// =====================================================
// FILE: app/Http/Middleware/IsAdmin.php
// =====================================================

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Login routes bypass
        if ($request->routeIs('admin.login') || $request->routeIs('admin.login.post')) {
            return $next($request);
        }

        // Web guard se check karo
        if (!Auth::guard('web')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access admin panel.');
        }

        // role_id = 1 means Admin
        if (Auth::guard('web')->user()->role_id != 1) {
            Auth::guard('web')->logout();
            return redirect()->route('admin.login')
                ->with('error', 'Access denied. Admins only.');
        }

        return $next($request);
    }
}