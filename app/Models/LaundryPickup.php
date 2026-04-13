<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaundryPickup extends Model
{
    protected $table = 'trans_laundry_pickups';
    protected $fillable = [
        'id_order',
        'id_customer',
        'pickup_date',
        'notes'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id');
    }
}
