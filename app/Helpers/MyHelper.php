<?php

use Carbon\Carbon;

/**fecha nombre día, día número y nombre mes  */
if (!function_exists('formatoFechaUno')) {
    function formatoFechaUno()
    {
        // Obtener la fecha actual en Carbon
        $fecha = Carbon::now();
        // Obtener el nombre del mes en español
        $mes = $fecha->locale('es')->monthName;
        $dia = $fecha->format('d');
        $nombreDia = ucfirst($fecha->dayName);
        return $nombreDia . ' ' . $dia . ' ' . $mes;
    }
}

if (!function_exists('formatoFechaDos')) {
    function formatoFechaDos($fecha)
    {
        // Obtener la fecha específica en Carbon (por ejemplo, una fecha específica)
        $fechaEspecifica = Carbon::parse($fecha);
        // Formatear la fecha según tus necesidades
        $nombreDia = ucfirst($fechaEspecifica->locale('es')->dayName);
        $dia = $fechaEspecifica->format('d');
        $mes = $fechaEspecifica->locale('es')->monthName;

        // Construir la cadena de fecha formateada
        $fechaFormateada = $nombreDia . ' ' . $dia . ' ' . $mes;

        // Devolver la fecha formateada
        return $fechaFormateada;
    }
}

if (!function_exists('fechas')) {
    function fechas($date = false)
    {
        // Obtener la fecha actual en Carbon
        if (!$date) {
            $fecha = Carbon::now();
        } else {
            $fecha = Carbon::parse($date);
        }

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
        return [$fecha, $formatoFecha, $formatoFecha1, $formatoFecha2];
    }
}

if (!function_exists('colorVencimiento')) {
    function colorVencimiento($dias)
    {
        $color = "text-bg-secondary";
        if ($dias > 14) {
            $color = "text-bg-danger";
        }
        return $color;
    }
}

if (!function_exists('colorPago')) {
    function colorPago($total)
    {
        $color = "text-bg-secondary";
        if ($total == 0) {
            $color = "text-bg-success";
        }
        return $color;
    }
}

/**
 * Obtener el intervalo de la semana basado en la fecha actual.
 *
 * @param DateTime $date
 * @return array
 */
if (!function_exists('getWeekInterval')) {
    function getWeekInterval()
    {
        $date = new DateTime();
        $startOfWeek = clone $date;
        $endOfWeek = clone $date;

        // Obtener el día de la semana (1 para Lunes, 7 para Domingo)
        $dayOfWeek = $date->format('N');

        // Calcular el inicio de la semana (Lunes)
        $startOfWeek->modify('-' . ($dayOfWeek - 1) . ' days');

        // Calcular el final de la semana (Sábado)
        $endOfWeek->modify('+' . (6 - $dayOfWeek + 1) . ' days');

        // Formatear las fechas
        $startFormatted = ucfirst(strftime('%A %d', $startOfWeek->getTimestamp()));
        $endFormatted = ucfirst(strftime('%A %d', $endOfWeek->getTimestamp()));

        // Formatear el nombre del mes
        $monthFormatted = ucfirst(strftime('%B', $startOfWeek->getTimestamp()));

        // Retornar el resultado formateado
        return "$startFormatted al $endFormatted de $monthFormatted";
    }
}
if (!function_exists('getWeekIntervalNumber')) {
    function getWeekIntervalNumber()
    {
        $date = new DateTime();
        $startOfWeek = clone $date;
        $endOfWeek = clone $date;

        // Obtener el día de la semana (1 para Lunes, 7 para Domingo)
        $dayOfWeek = $date->format('N');

        // Calcular el inicio de la semana (Lunes)
        $startOfWeek->modify('-' . ($dayOfWeek - 1) . ' days');

        // Calcular el final de la semana (Sábado)
        $endOfWeek->modify('+' . (6 - $dayOfWeek + 1) . ' days');

        // Formatear las fechas en formato YYYY/MM/DD
        $startFormatted = $startOfWeek->format('Y/m/d');
        $endFormatted = $endOfWeek->format('Y/m/d');

        // Retornar el resultado formateado
        return [$startFormatted, $endFormatted];
    }
}
