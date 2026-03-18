<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{Review, Booking, ProviderProfile};
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    use ApiResponse;
    public function index($providerId)
    {
        $provider = ProviderProfile::find($providerId);

        if (! $provider) return $this->notFound('Provider not found.');

        $reviews = Review::whereHas('booking', fn($q) => $q->where('provider_id', $providerId))
            ->with('reviewer:id,name,avatar')
            ->latest()
            ->paginate(10);

        return $this->paginated($reviews, 'Reviews fetched.');
    }

    public function store(Request $request, $bookingId)
    {
        $v = Validator::make($request->all(), [
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        if ($v->fails()) return $this->validationError($v->errors());

        $booking = Booking::where('customer_id', auth()->id())
            ->where('status', 'completed')
            ->find($bookingId);

        if (! $booking) return $this->notFound('Completed booking not found.');

        if ($booking->review) return $this->error('You have already reviewed this booking.', 422);

        $review = Review::create([
            'booking_id'  => $booking->id,
            'reviewer_id' => auth()->id(),
            'rating'      => $request->rating,
            'comment'     => $request->comment,
        ]);

        $avg = Review::whereHas('booking', fn($q) => $q->where('provider_id', $booking->provider_id))
            ->avg('rating');
        $booking->provider->update(['rating' => round($avg, 2)]);

        return $this->created($review, 'Review submitted.');
    }
}