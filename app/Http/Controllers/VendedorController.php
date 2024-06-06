<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class VendedorController extends Controller
{
    public function index()
    {
        // Configurar Carbon para el idioma español
        Carbon::setLocale('es');

        // Obtener la fecha actual en Carbon
        $fecha = Carbon::now();

        // Obtener el nombre del mes en español
        $mes = $fecha->locale('es')->monthName;
        $dia = $fecha->format('d');
        $año = $fecha->format('Y');
        $nombreDia = ucfirst($fecha->dayName);

        // Formatear la fecha en el formato deseado
        $formatoFecha = $fecha->translatedFormat('D d M');

        // Sumar 7 días para obtener dos fechas más
        $fecha1 = $fecha->copy()->addDays(7);
        $fecha2 = $fecha1->copy()->addDays(7);

        // Formatear las fechas adicionales
        $formatoFecha1 = $fecha1->translatedFormat('D d M');
        $formatoFecha2 = $fecha2->translatedFormat('D d M');

        $fecha = $nombreDia . ' ' . $dia . ' ' . ucwords($mes) . ', ' . $año;
        return view('vendedor.dashboard', ["fecha" => $fecha, "formatoFecha" => $formatoFecha, "formatoFecha1" => $formatoFecha1, "formatoFecha2" => $formatoFecha2]);
    }
}
