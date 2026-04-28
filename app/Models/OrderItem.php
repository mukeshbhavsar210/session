<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public function orders(){
        return $this->belongsTo(Order::class, 'order_id', );
    }

    public function assigned_seat(){
        return $this->belongsTo(Seat::class, 'seat_id', );
    }

    public function seat(){
        return $this->belongsTo(Seat::class);
    }

    protected $fillable = ['order_id', 'product_id', 'name', 'qty', 'price', 'total'];

}
