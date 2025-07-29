<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomAttribute extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    protected $table = 'attributes';

    /**
     * Get the parent attributeable model (modal or submodal).
     */

    public function advertisings()
    {
        return $this->belongsToMany(Advertising::class, 'advertisin_atribute')
            ->withPivot('value')
            ->withTimestamps();
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}


// $category = Category::find(1);
// $attributeId = 7;

// $category->attributes()->attach($attributeId, [
//     'is_custom' => true
// ]);


// $customAttributes = $category->attributes()->wherePivot('is_custom', true)->get();
