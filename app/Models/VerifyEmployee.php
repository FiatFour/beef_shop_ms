<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyEmployee extends Model
{
    use HasFactory;

    protected $fillable =[
        'emp_id',
        'token',
    ];

    public function admin(){
        return $this->belongsTo(Employee::class);
    }
}
