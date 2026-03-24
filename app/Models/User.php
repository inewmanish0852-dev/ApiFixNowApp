<?php
// =====================================================
// FILE: app/Models/User.php
// =====================================================

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'role_id', 'name', 'email', 'phone', 'password',
        'avatar', 'city', 'state', 'address', 'lat', 'lng',
        'is_verified', 'is_active', 'is_banned', 'ban_reason',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified'       => 'boolean',
        'is_active'         => 'boolean',
        'is_banned'         => 'boolean',
        'lat'               => 'decimal:7',
        'lng'               => 'decimal:7',
    ];

    // ── JWT ───────────────────────────────────────────────────────────────
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'role' => $this->role->slug,
            'name' => $this->name,
        ];
    }

    // ── Relations ─────────────────────────────────────────────────────────
    public function role()            { return $this->belongsTo(Role::class); }
    public function providerProfile() { return $this->hasOne(ProviderProfile::class); }
    public function provider()        { return $this->hasOne(Provider::class); }

    public function bookingsAsCustomer() { return $this->hasMany(Booking::class, 'customer_id'); }
    public function bookings()           { return $this->hasMany(Booking::class, 'customer_id'); }
    public function reviews()            { return $this->hasMany(Review::class, 'reviewer_id'); }
    public function notifications()      { return $this->hasMany(Notification::class); }

    // ── Helpers ───────────────────────────────────────────────────────────
    public function isAdmin():    bool { return $this->role->slug === 'admin'; }
    public function isProvider(): bool { return $this->role->slug === 'provider'; }
    public function isCustomer(): bool { return $this->role->slug === 'customer'; }

    public function getInitialAttribute(): string
    {
        return strtoupper(substr($this->name, 0, 1));
    }
}