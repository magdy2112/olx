<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Casts\Attribute;

class Submodal extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'sub_category_id'];
    public function updateFinalStatus()
    {
        $this->isfinal = 'final'; // آخر مستوى
        $this->save();
    }



  
        protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value),
        );
    }
    /**
     * Get the subcategory that owns the submodal.
     */
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    /**
     * Get the modal that owns the submodal.
     */
    public function modal()
    {
        return $this->belongsTo(Modal::class);
    }

    public function advertisings()
    {
        return $this->hasMany(Advertising::class);
    }
    /**
     * Get the attributes for the submodal.
     */
}
