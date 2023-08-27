<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyCustomer extends Model
{
    use HasFactory;

    protected $fillable =[
        'cus_id',
        'token',
    ];

    public function admin(){
        return $this->belongsTo(Customer::class);
    }
}
