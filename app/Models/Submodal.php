<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Submodal extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'sub_category_id'];
    public function updateFinalStatus()
    {
        $this->isfinal = 'final'; // آخر مستوى
        $this->save();
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
