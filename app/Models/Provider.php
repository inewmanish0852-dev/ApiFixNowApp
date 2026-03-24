<?php
// app/Models/Provider.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'user_id','business_name','bio','experience_years','service_area','hourly_rate',
        'id_proof_type','id_proof_number','id_proof_image','selfie_image','certificate_image',
        'verification_status','rejection_reason','verified_at','verified_by',
        'avg_rating','total_reviews','total_bookings','completed_bookings',
        'is_available','working_hours',
    ];
    protected $casts = [
        'verified_at'   => 'datetime',
        'is_available'  => 'boolean',
        'working_hours' => 'array',
        'avg_rating'    => 'float',
    ];

    public function user()     
    { 
        return $this->belongsTo(User::class,'user_id','id'); 
    }

    public function services() 
    { 
        return $this->belongsToMany(Service::class,'provider_services')->withPivot('custom_price','is_active')->withTimestamps(); 
    }

    public function bookings() 
    { 
        return $this->hasMany(Booking::class); 
    }
    
    public function reviews()  
    { 
        return $this->hasMany(Review::class); 
    }

    public function isVerified():  bool 
    { 
        return $this->verification_status === 'verified'; 
    }
    
    public function isPending():   bool 
    { 
        return $this->verification_status === 'pending'; 
    }
    
    public function isRejected():  bool 
    { 
        return $this->verification_status === 'rejected'; 
    }
    
    public function isSuspended(): bool 
    { 
        return $this->verification_status === 'suspended'; 
    }

    public function recalculateRating(): void
    {
        $avg = $this->reviews()->where('is_approved',true)->avg('rating') ?? 0;
        $cnt = $this->reviews()->where('is_approved',true)->count();
        $this->update(['avg_rating'=>round($avg,1),'total_reviews'=>$cnt]);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->verification_status) {
            'verified'  => 'green',
            'pending'   => 'orange',
            'rejected'  => 'red',
            'suspended' => 'purple',
            default     => 'gray',
        };
    }

    public function scopeVerified($q)   
    { 
        return $q->where('verification_status','verified'); 
    }
    
    public function scopePending($q)    
    { 
        return $q->where('verification_status','pending'); 
    }
    
    public function scopeSuspended($q)  
    { 
        return $q->where('verification_status','suspended'); 
    }
}