<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;
    protected $table = 'type_of_services';
    protected $fillable = [
        'service_name',
        'price',
        'description'
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'id_service', 'id');
    }
}
