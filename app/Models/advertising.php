<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;


class Advertising extends Model
{
    use Searchable;
    use HasFactory;

    protected $fillable = ['user_id', 'category_id', 'sub_category_id', 'modal_id', 'submodal_id',
        'title', 'description', 'price', 'purpose', 'status'];

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

    public function categoryattributes()
    {
        return $this->belongsToMany(CategoryAttribute::class, 'advertising_categoryattribute')
            ->withPivot('value')

            ->withTimestamps();
    }

    public function favs()
    {
        return $this->hasMany(Fav::class);
    }

  public function location()
{
    return $this->morphOne(Location::class, 'locationable');
}
public function toSearchableArray()
{
    return [
        'id'          => $this->id,
        'title'       => $this->title ?? '',
        'description' => $this->description ?? '',
        'price'       => $this->price ?? 0,
        'category_id' => $this->category_id ?? null,
        'modal_id'    => $this->modal_id ?? null,
        'submodal_id' => $this->submodal_id ?? null,
    ];
}


}