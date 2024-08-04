<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'descuentos';

    protected $fillable = [
        'id_asesor',
        'envases',
        'panos',
        'pendiente',
        'tardanza',
        'total',
        'parches',
        'creado_el',
    ];
}
