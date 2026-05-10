<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'brand',
        'price_usd',
        'stock',
        'short_description',
        'top_notes',
        'heart_notes',
        'base_notes',
        'gender',
        'images',
        'tags',
    ];

    protected $attributes = [
        'stock' => 20,
        'gender' => 'unisex',
    ];

    protected $casts = [
        'images' => 'array',
        'tags' => 'array',
        'price_usd' => 'decimal:2',
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
