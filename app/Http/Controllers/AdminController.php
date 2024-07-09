<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\GrupoUsuario;
use App\Models\GrupoUsuarioProducto;
use App\Models\Master;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $fecha = formatoFechaUno();
        $asociados = Usuario::where('rol', 2)->get();
        $cobradores = Usuario::where('rol', 3)->get();
        $today = Carbon::today()->toDateString();
        $grupos = Grupo::selectRaw('id,nombre,DATE(fecha) as fecha')->whereDate('fecha', $today)->get();
        return view('admin.dashboard', compact('fecha', 'asociados', 'grupos', 'cobradores'));
    }

    public function almacen()
    {
        return view('admin.almacen');
    }

    public function createAsociado(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:255',
            'clave' => 'required|string|max:255',
        ]);

        Usuario::create([
            'usuario' => $request->input('usuario'),
            'password' => $request->input('clave'),
            'estado' => 1,
            'rol' => 2,
        ]);

        return response()->json(['success' => true, 'message' => 'Datos recibidos correctamente']);
    }

    public function deleteAsociado(Request $request)
    {
        $usuario = Usuario::find($request->input('id'));
        if ($usuario) {
            $usuario->delete();
            return response()->json(['success' => true, 'message' => 'Usuario eliminado correctamente']);
        } else {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
        }
    }

    public function createGrupo(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:255',
        ]);

        $insertedId = DB::table('grupos')->insertGetId([
            'nombre' => $request->input('usuario'),
        ]);
        $this->createDetalleGrupo($insertedId, json_decode($request->vendedores), $request->idcobrador);
        return response()->json(['success' => true, 'message' => 'Datos recibidos correctamente', 'id' => $insertedId]);
    }

    public function deleteGrupo(Request $request)
    {
        $deleted = DB::table('grupos')->where('id', $request->input('id'))->delete();
        $existeGrupoUsuario = DB::table('grupo_usuario')->where('id_grupo', $request->input('id'))->get();
        if (count($existeGrupoUsuario)) {
            $idGrupoUsuario = $existeGrupoUsuario[0]->id;
            $existeGrupoProductos = DB::table('grupo_usuario_producto')->where('id_grupo_usuario', $idGrupoUsuario)->get();
            $deleted = DB::table('grupo_usuario')->where('id_grupo', $request->input('id'))->delete();
            if (count($existeGrupoProductos)) {
                $deleted = DB::table('grupo_usuario_producto')->where('id_grupo_usuario', $idGrupoUsuario)->delete();
            }
        }
        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Usuario eliminado correctamente']);
        } else {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
        }
    }

    public function createCobrador(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:255',
            'clave' => 'required|string|max:255',
        ]);

        Usuario::create([
            'usuario' => $request->input('usuario'),
            'password' => bcrypt($request->input('clave')),
            'estado' => 1,
            'rol' => 3,
        ]);

        return response()->json(['success' => true, 'message' => 'Datos recibidos correctamente']);
    }

    public function deleteCobrador(Request $request)
    {
        $usuario = Usuario::find($request->input('id'));
        if ($usuario) {
            $usuario->delete();
            return response()->json(['success' => true, 'message' => 'Usuario eliminado correctamente']);
        } else {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
        }
    }

    public function createDetalleGrupo($idGrupo, $usuarios, $idCobrador)
    {
        $productos = Producto::all();
        foreach ($usuarios as $usuario) {
            $insertedId = DB::table('grupo_usuario')->insertGetId([
                'id_grupo' => $idGrupo,
                'id_usuario' => $usuario->id
            ]);
            foreach ($productos as $producto) {
                DB::insert(
                    'insert into grupo_usuario_producto (id_grupo_usuario,id_producto,stock) values (?,?,?)',
                    [$insertedId, $producto->id, "{$usuario->stock}"]
                );
            }
        }
        DB::table('grupo_usuario')->insertGetId([
            'id_grupo' => $idGrupo,
            'id_usuario' => $idCobrador
        ]);
    }

    public function saveDetalle(Request $request)
    {
        Grupo::where('id', $request->id)->update([
            'deposito' => $request->depo,
            'taxi' => $request->taxi,
            'efectivo' => $request->efectivo,
        ]);
    }

    public function getGrupoDetalle(Request $request)
    {
        $id = $request->input('id');
        $datoGrupo = Grupo::where('id', $request->input('id'))->get();
        // Subconsulta para stock
        $stockSubquery = DB::table('grupo_usuario as gu')
            ->select('gu.id as grupo_usuario_id', DB::raw('SUM(gp.stock) as campo'))
            ->join('grupo_usuario_producto as gp', 'gp.id_grupo_usuario', '=', 'gu.id')
            ->where('gu.id_grupo', $id)
            ->groupBy('gu.id');

        // Subconsulta para vendidos
        $vendidosSubquery = DB::table('grupo_usuario as gu')
            ->select('gu.id as grupo_usuario_id', DB::raw('SUM(vd.cantidad) as vendido'))
            ->join('usuario as u', 'u.id', '=', 'gu.id_usuario')
            ->leftJoin('venta as v', function ($join) {
                $join->on('v.id_usuario', '=', 'u.id')
                    ->whereRaw('DATE(v.fecha) = CURRENT_DATE');
            })
            ->leftJoin('venta_detalle as vd', 'vd.id_venta', '=', 'v.id')
            ->where('gu.id_grupo', $id)
            ->where('u.rol', 2)
            ->groupBy('gu.id');

        // Consulta principal
        $detalle = DB::table('grupo_usuario as gu')
            ->select(
                'gu.id',
                'u.usuario',
                DB::raw('IFNULL(stock.campo, 0) as campo'),
                DB::raw('IFNULL(vendidos.vendido, 0) as vendidos'),
                DB::raw('(IFNULL(stock.campo, 0) - IFNULL(vendidos.vendido, 0)) as sobrantes')
            )
            ->join('usuario as u', 'u.id', '=', 'gu.id_usuario')
            ->leftJoinSub($stockSubquery, 'stock', function ($join) {
                $join->on('stock.grupo_usuario_id', '=', 'gu.id');
            })
            ->leftJoinSub($vendidosSubquery, 'vendidos', function ($join) {
                $join->on('vendidos.grupo_usuario_id', '=', 'gu.id');
            })
            ->where('gu.id_grupo', $id)
            ->where('u.rol', 2)
            ->groupBy('gu.id', 'u.usuario', 'stock.campo', 'vendidos.vendido')
            ->get();


        $ventas = DB::table('grupo_usuario as g')
            ->join('venta as v', 'v.id_usuario', '=', 'g.id_usuario')
            ->join('venta_detalle as vd', 'vd.id_venta', '=', 'v.id')
            ->selectRaw('
                SUM(CASE WHEN v.es_contado = 1 THEN vd.cantidad ELSE 0 END) AS contado,
                SUM(CASE WHEN v.es_contado = 0 THEN vd.cantidad ELSE 0 END) AS creditos
            ')
            ->where('g.id_grupo', $id) // Filtro por el ID de grupo pasado por request
            ->whereDate('v.fecha', now()) // Filtrar por la fecha actual
            ->groupBy('g.id_grupo') // Agrupar por el ID de grupo
            ->get();

        $ventaDetalle = DB::table('grupo_usuario as g')
            ->join('venta as v', 'v.id_usuario', '=', 'g.id_usuario')
            ->join('venta_detalle as vd', 'vd.id_venta', '=', 'v.id')
            ->selectRaw('
                SUM(CASE WHEN v.es_contado = 1 THEN (vd.cantidad * vd.precio) ELSE 0 END) AS efectivo,
                SUM(CASE WHEN v.estado = 0 THEN (vd.cantidad * vd.precio) ELSE 0 END) AS cobrar
            ')
            ->where('g.id_grupo', $id)
            ->whereDate('v.fecha', now())
            ->groupBy('g.id_grupo')
            ->get();
        return json_encode(["datoGrupo" => $datoGrupo, "detalle" => $detalle, "ventas" => $ventas, "ventaDetalle" => $ventaDetalle]);
    }

    public function saveMaster(Request $request)
    {
        foreach ($request->datos as $dato) {
            $existeUsuario = Master::where('id_usuario', $dato["id"])->first();
            if ($existeUsuario) {
                Master::where('id_usuario', $dato["id"])->update([
                    'cantidad' => $dato["stock"]
                ]);
            } else {
                Master::create([
                    'id_usuario' => $dato["id"],
                    'cantidad' => $dato["stock"]
                ]);
            }
        }
    }

    public function getMaster()
    {
        $usuarios = Usuario::leftJoin('master', 'usuario.id', '=', 'master.id_usuario')
            ->select('usuario.id', 'usuario.usuario', 'master.cantidad as stock')
            ->where('usuario.rol', 2)
            ->get()
            ->map(function ($usuario) {
                // Si no tiene stock en master, asigna 0
                if (is_null($usuario->stock)) {
                    $usuario->stock = 0;
                }
                return $usuario;
            });
        return json_encode($usuarios);
    }

    public function getMasterUsuario(Request $request)
    {
        $stock = Master::where('id_usuario', $request->id)->first();
        return $stock->cantidad;
    }

    public function updateDepositoTaxiGrupo(Request $request)
    {
        $grupo = Grupo::findOrFail($request->idGrupo);
        if (isset($request->deposito)) {
            $grupo->deposito = $request->deposito;
        }

        if (isset($request->taxi)) {
            $grupo->taxi = $request->taxi;
        }

        $grupo->save();

        return response()->json(['message' => 'Grupo actualizado correctamente']);
    }

    public function updateCampoUsuario(Request $request)
    {
        GrupoUsuarioProducto::where('id_grupo_usuario', $request->idUsuario)->update([
            'stock' => $request->campo
        ]);
    }

    public function getDetalleVendedor(Request $request)
    {
        $grupoUsuario = GrupoUsuario::find($request->idGrupoUsuario);
        $vendedor = Usuario::find($grupoUsuario->id_usuario);
        $grupo = Grupo::find($grupoUsuario->id_grupo);
        [$fecha] = fechas($grupo->fecha);
        $ventas = Venta::select('venta.id', 'venta.nombre', DB::raw('SUM(venta_detalle.cantidad) as total_cantidad'))
            ->join('venta_detalle', 'venta_detalle.id_venta', '=', 'venta.id')
            ->where('venta.id_usuario', $vendedor->id)
            ->where('venta.es_contado', 0)
            ->whereDate('venta.fecha', now())
            ->groupBy('venta.id', 'venta.nombre')
            ->get();

        $porCobrar = Venta::select('venta.id', 'venta.nombre', DB::raw('SUM(venta_detalle.cantidad) as total_cantidad'))
            ->join('venta_detalle', 'venta_detalle.id_venta', '=', 'venta.id')
            ->where('venta.id_usuario', $vendedor->id)
            ->where('venta.es_contado', 0)
            ->where('venta.estado', 0)
            ->whereDate('venta.fecha', now())
            ->groupBy('venta.id', 'venta.nombre')
            ->get();
        $contador = 0;
        foreach ($porCobrar as $cobrar) {
            $contador += $cobrar->total_cantidad;
        }

        $contado = Venta::select('venta.id', DB::raw('"Contado" as tipo_venta'), DB::raw('SUM(venta_detalle.cantidad) as total_cantidad'))
            ->join('venta_detalle', 'venta_detalle.id_venta', '=', 'venta.id')
            ->where('venta.id_usuario', $vendedor->id)
            ->where('venta.es_contado', 1)
            ->whereDate('venta.fecha', now())
            ->groupBy('venta.id', 'tipo_venta') // Agrupamos por venta.id y tipo_venta
            ->get();
        return response()->json(["vendedor" => $vendedor->usuario, 'fecha' => $fecha, 'ventas' => $ventas, 'contado' => $contado, 'porCobrar' => $contador]);
    }

    public function getVentas()
    {
        $ventas = Venta::select('venta.id', 'venta.nombre', 'usuario.usuario')
            ->join('usuario', 'usuario.id', '=', 'venta.id_usuario')
            ->whereDate('venta.fecha', now())
            ->where('venta.es_contado', 0)
            ->get();
        return response()->json($ventas);
    }
}
