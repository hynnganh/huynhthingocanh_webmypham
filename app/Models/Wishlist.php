<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Chặn tạo trùng bản ghi (user_id + product_id)
            if (Wishlist::where('user_id', $model->user_id)
                        ->where('product_id', $model->product_id)
                        ->exists()) {
                return false; // Không cho tạo nếu đã tồn tại
            }
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}


