<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'brand', 'price', 'stock', 'description', 'image', 'sub_image_1', 'sub_image_2', 'tags'];

    protected $attributes = [
        'stock' => 20,
    ];
    protected $casts = [
        'tags' => 'array',
    ];

    public function cartItems()
    {
    return $this->hasMany(CartItem::class);
    }
    
    public function orderItems()
    {
    return $this->hasMany(OrderItem::class);
    }
}
