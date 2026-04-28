<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function item(){
        return $this->hasMany(OrderItem::class);
    }

    public function items(){
        return $this->belongsTo(Order::class);
    }

    public function seat(){
        return $this->belongsTo(Seat::class, 'seat_id');
    }   
    
}