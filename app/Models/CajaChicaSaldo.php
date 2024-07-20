<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CajaChicaSaldo extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'caja_chica_saldo';

    protected $fillable = [
        'saldo',
        'fechaInicio',
        'fechaFin',
    ];
}
