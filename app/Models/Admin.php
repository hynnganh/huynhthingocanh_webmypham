<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $guard = 'admin';

    protected $fillable = [
        'name', 'email', 'password', 'roles'
    ];
}
