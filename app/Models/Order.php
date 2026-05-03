<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user()
    {
    return $this->belongsTo(User::class);
    }
    
    public function items()
    {
    return $this->hasMany(OrderItem::class);
    }
    protected $fillable = [
        'user_id',
        'total_price',
        'original_total_price',
        'voucher_code',
        'discount_percent',
        'status',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'payment_method',
    ];
}
