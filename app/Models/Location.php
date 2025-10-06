<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
  protected $fillable = ['city', 'country', 'lat', 'lng', 'governorate_id'];

    public function locationable()
    {
        return $this->morphTo();
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }
}
