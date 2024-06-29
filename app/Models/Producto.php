<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'productos'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'nombre',
        'descripcion',
        'abreviatura',
        'precio1',
        'precio2',
        'stock',
        'proveedor',
        'estado', 
    ];

    protected $casts = [
        'precio1' => 'float',
        'precio2' => 'float',
    ];
}
