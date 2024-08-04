<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'venta_detalle';

    protected $fillable = [
        'id_venta',
        'id_producto',
        'cantidad',
        'precio',
    ];
    
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }
}
