<?php
// =====================================================
// FILE: app/Http/Controllers/Admin/ReviewController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['customer', 'provider.user', 'booking'])
            ->latest()
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);
        $msg = $review->is_approved ? 'Review approved.' : 'Review hidden.';
        return back()->with('success', $msg);
    }

    public function flag(Review $review)
    {
        $review->update(['is_flagged' => !$review->is_flagged]);
        $msg = $review->is_flagged ? 'Review flagged.' : 'Review unflagged.';
        return back()->with('success', $msg);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}