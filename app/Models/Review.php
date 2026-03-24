<?php
// =====================================================
// FILE: app/Models/Review.php
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'booking_id', 'reviewer_id', 'rating', 'comment',

        // Admin panel ke liye add kiye gaye columns
        'provider_id', 'is_approved', 'is_flagged', 'flag_reason',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_flagged'  => 'boolean',
    ];

    // ── Relations ─────────────────────────────────────────────────────────
    public function booking()  { return $this->belongsTo(Booking::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewer_id'); }
    public function customer() { return $this->belongsTo(User::class, 'reviewer_id'); } // alias
    public function provider() { return $this->belongsTo(ProviderProfile::class, 'provider_id'); }

    // ── Scopes ────────────────────────────────────────────────────────────
    public function scopeApproved($q) { return $q->where('is_approved', true); }
    public function scopeFlagged($q)  { return $q->where('is_flagged', true); }
}