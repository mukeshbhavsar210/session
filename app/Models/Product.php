<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    protected $fillable = ['name','slug','category_id','menu_id','description','price','discounted_price','veg_nonveg','status'];

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
