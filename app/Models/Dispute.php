<?php
// =====================================================
// FILE: app/Models/Dispute.php
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $fillable = [
        'booking_id', 'raised_by', 'description', 'status',
        'admin_notes', 'resolution', 'resolved_by', 'resolved_at',
    ];

    protected $casts = ['resolved_at' => 'datetime'];

    public function booking()    { return $this->belongsTo(Booking::class); }
    public function raisedBy()   { return $this->belongsTo(User::class, 'raised_by'); }
    public function resolvedBy() { return $this->belongsTo(User::class, 'resolved_by'); }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'resolved'     => 'green',
            'under_review' => 'blue',
            'open'         => 'red',
            'closed'       => 'gray',
            default        => 'gray',
        };
    }

    public function scopeOpen($q) { return $q->where('status', 'open'); }
}