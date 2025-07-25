<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

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
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class)

            ->withTimestamps();
    }
}
