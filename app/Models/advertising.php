<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Advertising extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'category_id', 'sub_category_id', 'modal_id', 'submodal_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function modal()
    {
        return $this->belongsTo(Modal::class);
    }
    public function submodal()
    {
        return $this->belongsTo(Submodal::class);
    }
    public function images()
    {
        return $this->hasMany(image::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(CustomAttribute::class, 'advertising_attribute')
            ->withPivot('value')

            ->withTimestamps();
    }

    

    public function location()
    {
        return $this->morphOne(Location::class, 'locationable');
    }
}
