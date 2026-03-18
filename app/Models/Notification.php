<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id','title','body','type','ref_id','is_read'];
    protected $casts    = ['is_read' => 'boolean'];
}
