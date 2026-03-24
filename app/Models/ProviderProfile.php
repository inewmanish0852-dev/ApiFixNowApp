<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderProfile extends Model
{
    protected $fillable = [
        'user_id','category','bio','experience_years',
        'hourly_rate','rating','total_jobs','is_available',
    ];

    protected $casts = ['is_available' => 'boolean', 'rating' => 'decimal:2'];

    public function user()       { return $this->belongsTo(User::class); }
    public function skills()     { return $this->hasMany(ProviderSkill::class, 'provider_id'); }
    public function bookings()   { return $this->hasMany(Booking::class, 'provider_id'); }
}       