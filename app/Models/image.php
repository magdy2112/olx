<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class image extends Model
{
   protected $fillable = ['name', 'url', 'path', 'advertising_id'];
    public function advertising()
    {
        return $this->belongsTo(Advertising::class);
    }
}