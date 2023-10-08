<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_cow_id',
        'cow_id',
    ];


    public function orderCows(){
        return $this->hasMany(OrderCow::class);
    }

    public function cows(){
        return $this->hasMany(Cow::class);
    }

    public function age()
    {

        return Carbon::parse($this->attributes['birth'])->age;

    }
}
