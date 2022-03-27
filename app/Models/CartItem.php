<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $guarded = [];
    
    use HasFactory;

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
