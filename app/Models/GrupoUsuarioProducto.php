<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoUsuarioProducto extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'grupo_usuario_producto';

    protected $fillable = [
        'id_grupo_usuario',
        'id_producto',
        'stock',
        'vendido'
    ];
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id');
    }
}
