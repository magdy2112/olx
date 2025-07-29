<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

use function PHPUnit\Framework\isNull;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'isfinal', 'category_id'];
  
   protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value),
        );
    }

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
        return $this->hasManyThrough(Submodal::class, Modal::class);
    }

    public function advertisings()
    {
        return $this->hasMany(Advertising::class);
    }
   
}