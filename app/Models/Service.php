<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name' , 'min' , 'max' , 'rate' , 'refill'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class , 'likes')
            ->withTimestamps();
    }

    public function service_infos()
    {
        return $this->hasOne(ServiceInfo::class);
    }
}
