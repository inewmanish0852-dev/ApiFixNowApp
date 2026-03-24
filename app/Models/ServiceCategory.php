<?php
// =====================================================
// FILE: app/Models/ServiceCategory.php
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $fillable = [
        'name', 'slug', 'icon', 'image',
        'description', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    // ── Relations ─────────────────────────────────────────────────────────
    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }

    // ── Scopes ────────────────────────────────────────────────────────────
    public function scopeActive($q) { return $q->where('is_active', true); }
}