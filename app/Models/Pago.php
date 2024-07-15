<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'pagos_cliente';

    protected $fillable = [
        'id_cobrador',
        'id_venta',
        'abono',
        'pendiente',
        'creado_el',
    ];
}
