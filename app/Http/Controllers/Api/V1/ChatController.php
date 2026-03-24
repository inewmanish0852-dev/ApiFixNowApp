<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{Booking, ChatMessage};
use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Traits\ApiResponse;

class ChatController extends Controller
{
    use ApiResponse;
    // GET /chat/{bookingId}
    public function messages($bookingId)
    {
        $this->authorizeBooking($bookingId);

        $messages = ChatMessage::with('sender:id,name,avatar')
            ->where('booking_id', $bookingId)
            ->orderBy('created_at')
            ->get();

        // Mark incoming as read
        ChatMessage::where('booking_id', $bookingId)
            ->where('sender_id', '!=', auth()->id())
            ->update(['is_read' => true]);

        return response()->json(['success' => true, 'data' => $messages]);
    }

    // POST /chat/{bookingId}
    public function send(Request $request, $bookingId)
    {
        $this->authorizeBooking($bookingId);

        $request->validate(['message' => 'required|string|max:1000']);

        $msg = ChatMessage::create([
            'booking_id' => $bookingId,
            'sender_id'  => auth()->id(),
            'message'    => $request->message,
        ]);

        $booking = Booking::with('provider.user')->find($bookingId);

        // send() — message save ke baad
        $receiverId = $msg->sender_id === $booking->customer_id
            ? $booking->provider->user_id
            : $booking->customer_id;

        NotificationService::newMessage($msg, $receiverId);
        

        return response()->json([
            'success' => true,
            'data'    => $msg->load('sender:id,name,avatar'),
        ], 201);
    }

    private function authorizeBooking($bookingId)
    {
        $user    = auth('web')->user();
        $booking = Booking::findOrFail($bookingId);
        $allowed = $user->id === $booking->customer_id
                || $user->id === $booking->provider->user_id;

        abort_unless($allowed, 403, 'Access denied.');
    }
}