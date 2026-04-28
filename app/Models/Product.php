<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function seat(){
        return $this->hasMany(Seat::class);
    }

    public function product_images(){
        return $this->hasMany(ProductImage::class);
    }   

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function menu(){
        return $this->belongsTo(Menu::class);
    }
}
