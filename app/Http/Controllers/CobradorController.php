<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CobradorController extends Controller
{
    protected $id;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->id = Session::get('usuario_id');
            return $next($request);
        });
    }
    public function index()
    {
        [$fecha, $formatoFecha, $formatoFecha1, $formatoFecha2] = fechas();
        $id = $this->id;
        $grupo = DB::select("SELECT
                                g.id 
                            FROM
                                grupos g 
                            INNER JOIN grupo_usuario gu on gu.id_grupo = g.id
                            WHERE
                                gu.id_usuario = $id")[0];
        $ventas = $this->getVentas($grupo->id, $id);
        return view('cobrador.dashboard', compact("fecha", "formatoFecha", "formatoFecha1", "formatoFecha2", "ventas"));
    }

    public function filter(Request $request)
    {
        $filter = $request->input('filter');
        $where = '';
        if ($filter == '1RA Cuota') {
            $where = 'AND c.cuota3 != 0.00 ';
        }
        $grupo = DB::select("SELECT
                                g.id 
                            FROM
                                grupos g 
                            INNER JOIN grupo_usuario gu on gu.id_grupo = g.id
                            WHERE
                                gu.id_usuario = {$this->id}")[0];

        // Filtrar las ventas basadas en el filtro
        $ventas = $this->getVentas($grupo->id, $this->id, $where);

        return response()->json(['ventas' => $ventas]);
    }

    public function getVentas($grupoId, $id, $where = '')
    {
        return DB::select("SELECT
                                v.id,
                                v.nombre,
                                u.usuario,
                                SUM(c.cuota2) + SUM(c.cuota3) AS deuda,
                                DATEDIFF(CURDATE(), v.fecha) AS dias_desde_venta,
                                CASE
                                    WHEN DATEDIFF(CURDATE(), v.fecha) > 14 THEN 'text-bg-danger'
                                    ELSE 'text-bg-secondary'
                                END AS color_vencimiento,
                                COALESCE(SUM(p.abono), 0) AS total_pagos
                            FROM
                                grupo_usuario gu
                                INNER JOIN venta v ON v.id_usuario = gu.id_usuario
                                INNER JOIN usuario u ON u.id = v.id_usuario
                                INNER JOIN cuotas c ON c.id_venta = v.id
                                LEFT JOIN (
                                    SELECT id_venta, SUM(abono) AS abono
                                    FROM pagos_cliente
                                    GROUP BY id_venta
                                ) p ON p.id_venta = v.id
                            WHERE
                                gu.id_grupo = $grupoId 
                                AND gu.id_usuario != $id 
                                AND v.es_contado = 0
                                $where 
                            GROUP BY
                                v.id,
                                v.nombre,
                                u.usuario,
                                dias_desde_venta,
                                color_vencimiento");
    }

    public function detalleVenta(Request $request)
    {
        $id = $request->id;
        $venta = Venta::where('id', $id)->where('es_contado', 0)->first();
        if ($venta == null) {
            abort(404);
        }
        $vendedor = Usuario::where('id', $venta->id_usuario)->first();
        $detalle = DB::select("SELECT nombre,vd.precio,vd.cantidad 
                                FROM venta_detalle vd INNER JOIN productos p ON p.id = vd.id_producto 
                                WHERE vd.id_venta = {$venta->id} and vd.cantidad != 0");
        $cuotas = DB::table('cuotas')->where('id_venta', $venta->id)->get();

        [$fecha, $formatoFecha, $formatoFecha1, $formatoFecha2] = fechas($venta->fecha);

        $pagos = Pago::where('id_cobrador', $this->id)->where('id_venta', $request->id)->get();
        return response()->json(compact("venta", "vendedor", "detalle", "cuotas", "formatoFecha", "formatoFecha1", "formatoFecha2", 'pagos'));
    }

    public function savePago(Request $request)
    {
        $validated = $request->validate([
            'abono' => 'required|numeric|min:1',
            'pendiente' => 'required|numeric|min:0',
        ]);

        Pago::create([
            'id_cobrador' => $this->id,
            'id_venta' => $request->idVenta,
            'abono' => $validated['abono'],
            'pendiente' => $validated['pendiente'],
            'creado_el' => $request->fecha,
        ]);

        return response()->json(['message' => 'Abono registrado exitosamente.']);
    }

    public function updateEstadoVenta(Request $request)
    {
        $venta = Venta::findOrFail($request->idVenta);
        // Actualizar los campos del producto
        $venta->estado = 1;
        $venta->save();
    }
}
