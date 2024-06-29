<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoUsuario extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'grupo_usuario';

    protected $fillable = [
        'id_grupo',
        'id_usuario',
    ];

    // Relación muchos a uno con Grupo
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo');
    }
}
