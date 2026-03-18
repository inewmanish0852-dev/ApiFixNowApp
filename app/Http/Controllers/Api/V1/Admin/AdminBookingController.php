<?php
// AdminBookingController.php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class AdminBookingController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        $bookings = Booking::with(['customer:id,name,phone', 'provider.user:id,name,phone'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->from,   fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->to,     fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->latest()
            ->paginate(20);

        return $this->paginated($bookings, 'Bookings fetched.');
    }

    public function show($id)
    {
        echo 'Hello';exit;
        $booking = Booking::with(['customer','provider.user','review'])->findOrFail($id);
        return $this->success($booking);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:cancelled,completed']);
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => $request->status]);
        return $this->success($booking);
    }
}