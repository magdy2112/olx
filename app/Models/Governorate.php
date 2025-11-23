<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
     protected $fillable = ['city', 'country', 'lat', 'lng'];

     public function locations()
     {
         return $this->hasMany(Location::class);
     }


}
