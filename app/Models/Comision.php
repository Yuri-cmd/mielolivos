<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'comision';

    protected $fillable = [
        'id_asesor',
        'productos',
        'vendedor',
        'lider',
        'comision',
        'descuentos',
        'bono',
        'pefectivo',
        'pdeposito',
        'pfinal',
        'fecha',
    ];
}
