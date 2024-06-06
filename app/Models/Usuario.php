<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuario';
    protected $primaryKey = 'id';
    protected $fillable = [
        'usuario',
        'password',
        'estado',
        'rol',
    ];
}
