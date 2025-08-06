<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Casts\Attribute;
class Subattribute  extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'attribute_id'];
    protected $table = 'subattributes';

       protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    public function attribute()
    {
        return $this->belongsTo(CustomAttribute::class);
    }

    

}
