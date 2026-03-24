<?php
// =====================================================
// FILE: app/Http/Controllers/Admin/DashboardController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Provider;
use App\Models\Booking;
use App\Models\Dispute;

class DashboardController extends Controller
{
    // role_id values — roles table se match karta hai
    const ROLE_ADMIN    = 1;
    const ROLE_CUSTOMER = 2;
    const ROLE_PROVIDER = 3;

    public function index()
    {
        $stats = [
            'total_users'        => User::where('role_id', self::ROLE_CUSTOMER)->count(),
            'total_providers'    => Provider::count(),
            'pending_providers'  => Provider::pending()->count(),
            'total_bookings'     => Booking::count(),
            'active_bookings'    => Booking::whereIn('status', ['accepted', 'on_the_way', 'in_progress'])->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'total_revenue'      => Booking::where('payment_status', 'paid')->sum('total_amount'),
            'open_disputes'      => Dispute::where('status', 'open')->count(),
        ];

        $recent_bookings   = Booking::with(['customer', 'provider', 'service'])->latest()->take(6)->get();
        $pending_providers = Provider::with('user')->pending()->latest()->take(5)->get();
        $recent_disputes   = Dispute::with(['booking', 'raisedBy'])->where('status', 'open')->latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'stats', 'recent_bookings', 'pending_providers', 'recent_disputes'
        ));
    }
}