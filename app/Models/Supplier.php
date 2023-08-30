<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes; // Add this line to enable soft deletes
    protected $primaryKey = 'sup_id';

    protected $fillable = [
        'sup_id',
        'sup_name',
        'sup_address',
        'sup_tel',
    ];

    public function cows(){
        return $this->hasMany(Cow::class, 'sup_id', 'sup_id'); // sup_id(from Cow), sup_id(from Supplier)
    }

    // public function user(){ //? Join Query  (Eloquent)
    //     return $this->hasOne(User::class,'id','user_id');
    // }
}
