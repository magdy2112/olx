<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fav extends Model
{
    protected $fillable = ['user_id', 'advertising_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function advertising()
    {
        return $this->belongsTo(Advertising::class);
    }
}
