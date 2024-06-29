<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'grupos'; // Asumiendo que el nombre de la tabla en la base de datos es 'grupos'

    protected $fillable = [
        'nombre',
        'fecha',
        'deposito',
        'taxi',
        'efectivo',
    ];

    // Relación uno a muchos con GrupoUsuario
    public function grupoUsuarios()
    {
        return $this->hasMany(GrupoUsuario::class, 'id_grupo');
    }
}
