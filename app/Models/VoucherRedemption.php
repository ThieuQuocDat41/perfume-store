<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherRedemption extends Model
{
    protected $table = 'voucher_redemptions';
    protected $fillable = ['voucher_id','user_id','used_at'];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
