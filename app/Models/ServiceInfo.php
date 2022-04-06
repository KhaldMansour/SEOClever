<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceInfo extends Model
{
    use HasFactory;

    protected $fillable =['start_date' , 'approximate_time' , 'speed' , 'quality' , 'rating' , 'description' , 'guarantee'];

    public function service()
    {
        return $this->belongsTo(ServiceInfo::class);
    }
}
