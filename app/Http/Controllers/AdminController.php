<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Obtener la fecha actual en Carbon
        $fecha = Carbon::now();

        // Obtener el nombre del mes en español
        $mes = $fecha->locale('es')->monthName;
        $dia = $fecha->format('d');
        $nombreDia = ucfirst($fecha->dayName);
        return view('admin.dashboard', ["mes" => $mes, "dia" => $dia, "nombreDia" => $nombreDia]);
    }
}
