<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'post'; // tên bảng nếu không theo convention

    protected $fillable = [
        'topic_id',
        'title',
        'slug',
        'detail',
        'thumbnail',
        'type',
        'description',
        'created_by',
        'updated_by',
        'status'
    ];
}
