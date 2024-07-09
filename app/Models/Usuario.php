<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    public $timestamps = false;
    protected $table = 'usuario';
    protected $primaryKey = 'id';
    protected $fillable = [
        'usuario',
        'password',
        'estado',
        'rol',
    ];
    
    public function masters()
    {
        return $this->hasMany(Master::class, 'id_usuario', 'id');
    }
}
