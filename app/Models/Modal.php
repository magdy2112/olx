<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Modal extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'sub_category_id'];

    public function updateFinalStatus()
    {
        $this->isfinal = $this->submodals()->count() == 0 ? 'final' : 'not_final';
        $this->save();
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