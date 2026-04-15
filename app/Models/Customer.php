<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'cutomer_name',
        'phone',
        'address'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'id_customer', 'id');
    }

    public function laundryPickups()
    {
        return $this->hasMany(LaundryPickup::class, 'id_customer', 'id');
    }
}
