<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cow extends Model
{
    use HasFactory;

    protected $fillable = [
        'cow_gene',
        'cow_img',
        'cow_birth',
        'sup_id',
    ];

    public function supplier(){
        return $this->belongsTo(Supplier::class, 'sup_id', 'id');
    }
}
