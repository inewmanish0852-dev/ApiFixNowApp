<?php
// =====================================================
// FILE: app/Http/Controllers/Admin/BookingController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'provider.user', 'service'])->latest();

        if ($request->filled('status'))
            $query->where('status', $request->status);

        if ($request->filled('search'))
            $query->where('booking_number', 'like', "%{$request->search}%");

        if ($request->filled('date'))
            $query->whereDate('booking_date', $request->date);

        $bookings = $query->paginate(20)->withQueryString();

        $stats = [
            'total'     => Booking::count(),
            'pending'   => Booking::where('status', 'pending')->count(),
            'active'    => Booking::whereIn('status', ['accepted', 'on_the_way', 'in_progress'])->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'disputed'  => Booking::where('status', 'disputed')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['customer', 'provider.user', 'service', 'review', 'dispute.raisedBy']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking)
    {
        $request->validate(['reason' => 'required|string']);

        $booking->update([
            'status'              => 'cancelled',
            'cancellation_reason' => $request->reason,
            'cancelled_by'        => 'admin',
        ]);

        return back()->with('success', "Booking {$booking->booking_number} cancelled.");
    }
}