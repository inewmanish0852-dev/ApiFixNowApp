<?php
// AdminDashboardController.php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Booking, ProviderProfile};

class AdminDashboardController extends Controller
{
    // AdminDashboardController
    public function index()
    {
        return $this->success([
            'total_users'     => User::count(),
            'total_providers' => User::whereHas('role', fn($q) => $q->where('slug','provider'))->count(),
            'total_customers' => User::whereHas('role', fn($q) => $q->where('slug','customer'))->count(),
            'total_bookings'  => Booking::count(),
            'completed_jobs'  => Booking::where('status','completed')->count(),
            'pending_jobs'    => Booking::where('status','pending')->count(),
            'total_revenue'   => Booking::where('status','completed')->sum('amount'),
            'today_bookings'  => Booking::whereDate('created_at', today())->count(),
        ], 'Dashboard data fetched.');
    }
}