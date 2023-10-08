<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCow extends Model
{
    use HasFactory;

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_cow_id', 'id');
    }
}
