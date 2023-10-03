<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cow extends Model
{
    use HasFactory;

    protected $fillable = [
        'cow_gene',
        'cow_img',
        'birth',
        'sup_id',
    ];

    // public function supplier(){
    //     return $this->belongsTo(Supplier::class, 'sup_id', 'id');
    // }
    public function age()
    {

        return Carbon::parse($this->attributes['birth'])->age;

    }

    public function costOfFood(){
        $dissectDate =Carbon::parse($this->attributes['dissect_date']);
        $createdAt = Carbon::parse($this->attributes['created_at']);
        $diffInDays = $createdAt->diffInDays($dissectDate);

        return $diffInDays * 100;
    }

}
