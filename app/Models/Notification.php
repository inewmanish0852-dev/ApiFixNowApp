<?php
// =====================================================
// FILE: app/Models/Notification.php
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'title', 'body', 'type',
        'ref_id', 'is_read',

        // Admin panel ke liye add kiye gaye columns
        'icon', 'action_url',
    ];

    protected $casts = ['is_read' => 'boolean'];

    // ── Relations ─────────────────────────────────────────────────────────
    public function user() { return $this->belongsTo(User::class); }

    // ── Scopes ────────────────────────────────────────────────────────────
    public function scopeUnread($q) { return $q->where('is_read', false); }

    public function scopeForUser($q, $userId)
    {
        return $q->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)->orWhereNull('user_id');
        });
    }
}