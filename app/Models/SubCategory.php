<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

use function PHPUnit\Framework\isNull;

class SubCategory extends Model
{
    use HasFactory;

    // protected function isfinal(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn() => is_null($this->category_id) ? 'final' : 'not_final',
    //         set: fn($value) => $value
    //     );
    // }
    protected $fillable = ['name', 'slug', 'category_id'];


    public function updateFinalStatus()
    {
        $this->isfinal = $this->modals()->count() == 0 ? 'final' : 'not_final';
        $this->save();
    }

    /**
     * Get the category that owns the subcategory.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the modals for the subcategory.
     */
    public function modals()
    {
        return $this->hasMany(Modal::class);
    }


    /**
     * Get the submodals for the subcategory.
     */
    public function submodals()
    {
        return $this->hasMany(Submodal::class);
    }
    /**
     * Get the attributes for the subcategory.
     */
}