<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';
    protected $fillable = [
        'voucher_code',
        'discount_precentage',
        'is_active',
        'expired_at',
    ];

    public function transactions()
    {
        return $this->hasMany(Order::class, 'id_voucher', 'id');
    }

    public function scopeActive(Builder $query, string $code): Builder
    {
        return $query->where('voucher_code', $code)
            ->where('is_active', true)
            ->whereDate('expired_at', '>=', now());
    }
}
