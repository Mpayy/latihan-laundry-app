<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $table = 'trans_orders';
    protected $fillable = [
        'id_customer',
        'order_code',
        'order_date',
        'order_end_date',
        'order_status',
        'order_pay',
        'order_change',
        'total'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id');
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'id_order', 'id');
    }

    public function laundryPickups()
    {
        return $this->hasMany(LaundryPickup::class, 'id_order', 'id');
    }
}
