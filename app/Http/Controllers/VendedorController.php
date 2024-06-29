<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VendedorController extends Controller
{
    public function index()
    {
        // Configurar Carbon para el idioma español
        Carbon::setLocale('es');

        [$fecha, $formatoFecha, $formatoFecha1, $formatoFecha2] = fechas();

        $id = Session::get('usuario_id');
        $detalle = DB::select("SELECT
                                gp.id,
                                gp.id_producto,
                                (stock - vendido) AS sobrantes 
                            FROM
                            grupo_usuario g 
                            INNER JOIN grupo_usuario_producto gp on gp.id_grupo_usuario = g.id
                            where 
                            g.id_usuario = $id");
        $productos = Producto::all();
        $chunks = $productos->chunk(ceil($productos->count() / 2));
        $productos1 = $chunks->get(0);
        $productos2 = $chunks->get(1);
        return view('vendedor.dashboard', compact("fecha", "formatoFecha", "formatoFecha1", "formatoFecha2", "productos1", "productos2"));
    }

    public function saveVenta(Request $request)
    {
        $signatureData = $request->signatureData;

        // Eliminar la parte 'data:image/png;base64,' del string base64
        $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
        $signatureData = str_replace(' ', '+', $signatureData);

        $signatureName = 'signature_' . time() . '.png';
        $signaturePath = 'signatures/' . $signatureName;

        // Guardar la imagen en el almacenamiento público
        Storage::disk('public')->put($signaturePath, base64_decode($signatureData));

        $insertedId = DB::table('venta')->insertGetId([
            'id_usuario' => Session::get('usuario_id'),
            'nombre' => $request->datosVenta['nombre'],
            'mz' => $request->datosVenta['mz'],
            'lt' => $request->datosVenta['lt'],
            'jr' => $request->datosVenta['jr'],
            'piso' => $request->datosVenta['piso'],
            'pisos' => $request->datosVenta['pisos'],
            'urb' => $request->datosVenta['urb'],
            'color' => $request->datosVenta['color'],
            'tocar' => $request->datosVenta['tocar'],
            'telefono' => $request->datosVenta['tel'],
            'firma' => $signatureName
        ]);

        foreach ($request->detalle as $detalle) {
            DB::insert(
                'insert into venta_detalle (id_venta,id_producto,cantidad,precio) values (?,?,?,?)',
                [$insertedId, $detalle["id"], "{$detalle["cantidad"]}", "{$detalle["precio"]}"]
            );
        }

        DB::insert(
            'insert into cuotas (id_venta,cuota1,cuota2,cuota3) values (?,?,?,?)',
            [$insertedId, "{$request->cuotas['cuota1']}", "{$request->cuotas['cuota2']}", "{$request->cuotas['cuota2']}"]
        );
        return json_encode($insertedId);
    }

    public function seeVenta($id)
    {
        $venta = DB::table('venta')->where('id', $id)->get();
        $vendedor = Usuario::where('id', $venta[0]->id_usuario)->first();
        $detalle = DB::select("SELECT nombre,vd.precio,vd.cantidad 
                                FROM venta_detalle vd INNER JOIN productos p ON p.id = vd.id_producto 
                                WHERE vd.id_venta = {$venta[0]->id} and vd.cantidad != 0");
        $cuotas = DB::table('cuotas')->where('id_venta', $venta[0]->id)->get();

        [$fecha, $formatoFecha, $formatoFecha1, $formatoFecha2] = fechas($venta[0]->fecha);
        return view('vendedor.boleta', compact("venta", "vendedor", "detalle", "cuotas", "formatoFecha", "formatoFecha1", "formatoFecha2"));
    }
}
