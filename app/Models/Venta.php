<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'venta';

    protected $fillable = [
        'id_usuario',
        'nombre',
        'mz',
        'lt',
        'jr',
        'piso',
        'pisos',
        'urb',
        'color',
        'tocar',
        'telefono',
        'firma',
        'estado',
        'fecha',
        'es_contado'
    ];
}
