<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

     protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    /**
     * Get the subcategories for the category.
     */
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    /**
     * Get the modals for the category.
     */
    public function modals()
    {
        return $this->hasManyThrough(Modal::class, SubCategory::class);
    }

    public function advertisings()
    {
        return $this->hasMany(Advertising::class);
    }
    /**
     * Get the attributes for the category.
     */
    // public function attributes()
    // {
    //     return $this->belongsToMany(CustomAttribute::class,'attribute_category')

    //         ->withTimestamps();
    // }
    public function attributes()
    {
        return $this->hasMany(CustomAttribute::class);
    }
}
