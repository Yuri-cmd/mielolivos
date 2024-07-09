<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
class CobradorController extends Controller
{
    public function index()
    {
        [$fecha, $formatoFecha, $formatoFecha1, $formatoFecha2] = fechas();
        $id = Session::get('usuario_id');
        $grupo = DB::select("SELECT
                                    g.id 
                                FROM
                                    grupos g 
                                INNER JOIN grupo_usuario gu on gu.id_grupo = g.id
                                WHERE
                                    DATE(g.fecha) = CURRENT_DATE
                                    and gu.id_usuario = $id")[0];
        $ventas = DB::select("SELECT
                            v.id,
                            v.nombre,
                            u.usuario,
                            SUM(c.cuota1) + SUM(c.cuota2) + SUM(c.cuota3) as deuda,
                            DATEDIFF(CURDATE(), v.fecha) AS dias_desde_venta
                        FROM
                            grupo_usuario gu
                            INNER JOIN venta v ON v.id_usuario = gu.id_usuario
                            INNER JOIN usuario u on u.id = v.id_usuario
                            INNER JOIN cuotas c ON c.id_venta = v.id 
                        WHERE
                            gu.id_grupo = {$grupo->id} 
                            AND gu.id_usuario != $id
                            AND v.es_contado = 0
                        GROUP BY
                            v.id, v.nombre");
        return view('cobrador.dashboard', compact("fecha", "formatoFecha", "formatoFecha1", "formatoFecha2", "ventas"));
    }
}
