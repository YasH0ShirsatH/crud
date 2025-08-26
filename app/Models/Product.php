<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name'
    ];
     public function photos(){
        return $this->morphMany(Photo::class,'imageable');
    }
}
