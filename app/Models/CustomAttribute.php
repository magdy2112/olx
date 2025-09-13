<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Casts\Attribute;
class CustomAttribute extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'sub_category_id'];
    protected $table = 'attributes';

       protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    /**
     * Get the parent attributeable model (modal or submodal).
     */

    public function advertisings()
    {
        return $this->belongsToMany(Advertising::class, 'advertising_attribute')
            ->withPivot('value')
            ->withTimestamps();
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class);
    }
 
  
}



