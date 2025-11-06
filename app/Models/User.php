<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // ⚙️ Vì bảng là 'user' chứ không phải 'users'
    protected $table = 'user';

    // ⚙️ Thêm trường 'roles' để kiểm tra quyền admin
    protected $fillable = [
        'name',
        'email',
        'password',
        'roles',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Quan hệ
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function wishlist()
    {
        return $this->hasMany(\App\Models\Wishlist::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
