<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'trans_order_details';
    protected $fillable = [
        'id_order',
        'id_service',
        'qty',
        'subtotal',
        'notes'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id');
    }
}
