<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    protected $table = 'menu';
    use SoftDeletes;

    public function parent()
{
    return $this->belongsTo(Menu::class, 'parent_id');
}

protected $fillable = [
    'name', 'link', 'table_id', 'parent_id', 'type', 'status', 'position', 'created_by', 'updated_by'
];

}
