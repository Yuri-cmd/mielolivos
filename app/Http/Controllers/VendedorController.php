<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\GrupoUsuario;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VendedorController extends Controller
{
    public function index()
    {
        [$fecha, $formatoFecha, $formatoFecha1, $formatoFecha2] = fechas();

        $id = Session::get('usuario_id');
        $usuarioGrupo = GrupoUsuario::where('id_usuario', $id)->get();
        $gruposHoy = Grupo::whereDate('fecha', Carbon::today())
            ->whereHas('grupoUsuarios', function ($query) use ($id) {
                $query->where('id_usuario', $id);
            })
            ->get();
        $cantidadVentas = $this->getCantidadVentasUsuario($id);
        $detalle = DB::table('grupo_usuario as gu')
            ->select('gp.id', 'gp.id_producto', DB::raw('(gp.stock - gp.vendido) as sobrantes'))
            ->join('grupo_usuario_producto as gp', 'gp.id_grupo_usuario', '=', 'gu.id')
            ->where('gu.id_usuario', $id)
            ->get();
        $productos = Producto::all();
        $chunks = $productos->chunk(ceil($productos->count() / 2));
        $productos1 = $this->getProductos($detalle, $chunks->get(0));
        $productos2 = $this->getProductos($detalle, $chunks->get(1));
        return view('vendedor.dashboard', compact("fecha", "formatoFecha", "formatoFecha1", "formatoFecha2", "productos1", "productos2", "cantidadVentas", "gruposHoy"));
    }

    public function getCantidadVentasUsuario($id)
    {
        $ventas = Venta::where('id_usuario', $id)
            ->whereDate('fecha', Carbon::today())
            ->get();
        return count($ventas);
    }

    public function getProductos($detalle, $productos)
    {
        foreach ($detalle as $d) {
            foreach ($productos as $p) {
                if ($d->id_producto == $p->id) {
                    $p->sobrante = $d->sobrantes;
                }
            }
        }
        return $productos;
    }

    public function saveVenta(Request $request)
    {
        $id_usuario = Session::get('usuario_id');
        $tipo = $request->tipo == 'contado';
        $signatureName = '';
        if (!$tipo) {
            $signatureName = $this->saveSignature($request->signatureData);
        }
        $estado = $tipo ? 1 : 0;
        $es_contado = $tipo ? 1 : 0;
        $data = [
            'id_usuario' => $id_usuario,
            'nombre' => $request->input('datosVenta.nombre'),
            'mz' => $request->input('datosVenta.mz'),
            'lt' => $request->input('datosVenta.lt'),
            'jr' => $request->input('datosVenta.jr'),
            'piso' => $request->input('datosVenta.piso'),
            'pisos' => $request->input('datosVenta.pisos'),
            'urb' => $request->input('datosVenta.urb'),
            'color' => $request->input('datosVenta.color'),
            'tocar' => $request->input('datosVenta.tocar'),
            'telefono' => $request->input('datosVenta.tel'),
            'firma' => $signatureName,
            'estado' => $estado,
            'es_contado' => $es_contado
        ];
        // Filtrar los campos vacíos
        $data = array_filter($data, function ($value) {
            return !is_null($value) && $value !== '';
        });

        // Insertar los datos usando el modelo y obtener el ID insertado
        $venta = Venta::create($data);

        // Obtener el ID de la nueva inserción
        $insertedId = $venta->id;
        $GrupoUsuario = GrupoUsuario::where('id_grupo', $request->idGrupo)->first();

        foreach ($request->detalle as $detalle) {
            DB::insert(
                'insert into venta_detalle (id_venta,id_producto,cantidad,precio) values (?,?,?,?)',
                [$insertedId, $detalle["id"], "{$detalle["cantidad"]}", "{$detalle["precio"]}"]
            );
            $GrupoUsuarioProducto = DB::table('grupo_usuario_producto')->where('id_grupo_usuario', $GrupoUsuario->id)->where('id_producto', $detalle["id"])->first();
            $vendido =  $GrupoUsuarioProducto->vendido;
            DB::table('grupo_usuario_producto')
                ->where('id_grupo_usuario', $GrupoUsuario->id)
                ->where('id_producto', $detalle["id"])
                ->update([
                    'vendido' => $vendido + (int)$detalle["cantidad"],
                ]);
        }
        if (!$tipo) {
            DB::insert(
                'insert into cuotas (id_venta,cuota1,cuota2,cuota3) values (?,?,?,?)',
                [$insertedId, "{$request->cuotas['cuota1']}", "{$request->cuotas['cuota2']}", "{$request->cuotas['cuota2']}"]
            );
        }
        return json_encode($insertedId);
    }

    public function seeVenta($id)
    {
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
        return view('vendedor.boleta', compact("venta", "vendedor", "detalle", "cuotas", "formatoFecha", "formatoFecha1", "formatoFecha2"));
    }

    public function saveSignature($signatureData)
    {
        $signatureData = $signatureData;
        // Eliminar la parte 'data:image/png;base64,' del string base64
        $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
        $signatureData = str_replace(' ', '+', $signatureData);

        $signatureName = 'signature_' . time() . '.png';
        $signaturePath = 'signatures/' . $signatureName;

        // Guardar la imagen en el almacenamiento público
        Storage::disk('public')->put($signaturePath, base64_decode($signatureData));

        return $signatureName;
    }
}
