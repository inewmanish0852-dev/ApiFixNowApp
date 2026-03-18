<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'customer_id','provider_id','service_type','scheduled_at',
        'status','amount','address','notes',
    ];

    protected $casts = ['scheduled_at' => 'datetime'];

    public function customer()  { return $this->belongsTo(User::class, 'customer_id'); }
    public function provider()  { return $this->belongsTo(ProviderProfile::class, 'provider_id'); }
    public function review()    { return $this->hasOne(Review::class); }
}