<?php
// =====================================================
// FILE: app/Models/Service.php
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'base_price', 'price_unit', 'image',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'base_price' => 'float',
    ];

    // ── Relations ─────────────────────────────────────────────────────────
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'provider_services')
            ->withPivot('custom_price', 'is_active')
            ->withTimestamps();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────
    public function scopeActive($q) { return $q->where('is_active', true); }
}