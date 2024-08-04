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
        'id_grupo',
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

    public function ventaDetalles()
    {
        return $this->hasMany(VentaDetalle::class, 'id_venta');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_venta');
    }
}
