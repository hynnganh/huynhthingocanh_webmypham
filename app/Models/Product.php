<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    protected $table = 'product';
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'brand_id', 'name', 'slug',
        'price_root', 'price_sale', 'thumbnail',
        'qty', 'detail', 'description',
        'created_by', 'updated_by', 'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}
