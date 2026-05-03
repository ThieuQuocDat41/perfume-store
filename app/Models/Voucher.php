<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = ['code','percent'];

    public function redemptions()
    {
        return $this->hasMany(VoucherRedemption::class);
    }
}
