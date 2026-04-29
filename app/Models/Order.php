<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function product_images(){
        return $this->hasMany(ProductImage::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    // public function items(){
    //     return $this->belongsTo(Order::class);
    // }

    public function seat(){
        return $this->belongsTo(Seat::class, 'seat_id');
    }   
    
}