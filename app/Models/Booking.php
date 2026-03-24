<?php
// =====================================================
// FILE: app/Models/Booking.php
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'customer_id', 'provider_id', 'service_type',
        'scheduled_at', 'status', 'amount', 'address', 'notes',

        // Admin panel ke liye add kiye gaye columns
        'booking_number', 'payment_status', 'payment_method',
        'total_amount', 'platform_fee', 'service_charge',
        'cancellation_reason', 'cancelled_by',
        'started_at', 'completed_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
        'total_amount' => 'float',
        'amount'       => 'float',
        'platform_fee' => 'float',
    ];

    // ── Relations ─────────────────────────────────────────────────────────
    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function service() { return $this->belongsTo(Service::class, 'service_id'); }
    public function provider() { return $this->belongsTo(ProviderProfile::class, 'provider_id'); }
    public function review()   { return $this->hasOne(Review::class); }
    public function dispute()  { return $this->hasOne(Dispute::class); }

    // ── Accessors ─────────────────────────────────────────────────────────
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'completed'                  => 'green',
            'in_progress', 'on_the_way' => 'blue',
            'accepted'                   => 'teal',
            'pending'                    => 'orange',
            'cancelled'                  => 'red',
            'disputed'                   => 'purple',
            default                      => 'gray',
        };
    }

    // ── Helpers ───────────────────────────────────────────────────────────
    public static function generateNumber(): string
    {
        $count = static::whereYear('created_at', date('Y'))->count() + 1;
        return 'BK-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}