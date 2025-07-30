<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Modal extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'sub_category_id'];

    public function updateFinalStatus()
    {
        $this->isfinal = $this->submodals()->count() == 0 ? 'final' : 'not_final';
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
     * Get the subcategory that owns the modal.
     */
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    /**
     * Get the submodals for the modal.
     */
    public function submodals()
    {
        return $this->hasMany(Submodal::class);
    }

    public function advertisings()
    {
        return $this->hasMany(Advertising::class);
    }

    /**
     * Get the attributes for the modal.
     */
}