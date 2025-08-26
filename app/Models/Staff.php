<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'name'
    ];
    public function photos(){
        return $this->morphMany(Photo::class,'imageable');
    }
}
