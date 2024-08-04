<?php

namespace App\Http\Controllers;

use App\Models\CajaChica;
use App\Models\CajaChicaSaldo;
use App\Models\Comision;
use App\Models\Descuento;
use App\Models\Grupo;
use App\Models\GrupoUsuario;
use App\Models\GrupoUsuarioProducto;
use App\Models\Master;
use App\Models\Pago;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

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
        foreach ($usuarios as $i => $usuario) {
            $insertedId = DB::table('grupo_usuario')->insertGetId([
                'id_grupo' => $idGrupo,
                'id_usuario' => $usuario->id,
                'lider' => $i == 0 ? 1 : 0
            ]);
            foreach ($productos as $producto) {
                $stock = $usuario->stock / 10;
                DB::insert(
                    'insert into grupo_usuario_producto (id_grupo_usuario,id_producto,stock) values (?,?,?)',
                    [$insertedId, $producto->id, "{$stock}"]
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
        $usuarios = Usuario::where('rol', 2)->get();
        foreach ($usuarios as $usuario) {
            $existeUsuario = Master::where('id_usuario', $usuario->id)->first();
            foreach ($request->datos as $dato) {
                if ($existeUsuario) {
                    Master::where('id_usuario', $usuario->id)->where('id_producto', $dato["id"])->update([
                        'cantidad' => $dato["stock"]
                    ]);
                } else {
                    Master::create([
                        'id_producto' => $dato["id"],
                        'id_usuario' => $usuario->id,
                        'cantidad' => $dato["stock"]
                    ]);
                }
            }
        }
    }

    public function getMaster()
    {
        $productos = Producto::leftJoin('master', 'productos.id', '=', 'master.id_producto')
            ->select('productos.id', 'productos.nombre', 'master.cantidad as stock')
            ->get()
            ->map(function ($producto) {
                // Si no tiene stock en master, asigna 0
                if (is_null($producto->stock)) {
                    $producto->stock = 0;
                }
                return $producto;
            });
        // Convertir la colección en un array para usar array_unique
        $productosArray = $productos->toArray();

        // Definir una función de comparación para identificar duplicados
        function compareProducts($a, $b)
        {
            return $a['id'] === $b['id'];
        }

        // Eliminar duplicados
        $uniqueProducts = array_values(array_intersect_key($productosArray, array_unique(array_column($productosArray, 'id'))));
        return json_encode($uniqueProducts);
    }

    public function getMasterUsuario(Request $request)
    {
        $stocks = 0;
        $productos = Master::where('id_usuario', $request->id)->get();
        foreach ($productos as $producto) {
            $stocks += $producto->cantidad;
        }
        return $stocks;
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
        $productos = json_decode($request->data);
        foreach ($productos as $producto) {
            preg_match('/\d+/', $producto->stock_id, $matches);
            $idProducto = $matches[0] ?? null;
            GrupoUsuarioProducto::where('id_grupo_usuario', $request->idUsuario)
                ->where('id_producto', $idProducto)
                ->update(['stock' => $producto->stock_value]);
        }
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
            ->where('venta.es_contado', 0)
            ->where('venta.es_oficina', 0)
            ->get();
        return response()->json($ventas);
    }

    public function buscarUsuarios(Request $request)
    {
        $query = $request->input('query');
        $usuarios = Usuario::where('usuario', 'LIKE', "%{$query}%")->where('rol', 2)->get();
        return response()->json($usuarios);
    }

    public function getCajaChica()
    {
        [$fechaInicio, $fechaFin] = getWeekIntervalNumber();
        $caja = CajaChica::whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
        $saldo = CajaChicaSaldo::where('fechaInicio', $fechaInicio)->where('fechaFin', $fechaFin)->get();
        return response()->json(["caja" => $caja, "saldo" => $saldo]);
    }

    public function saveCajaChica(Request $request)
    {
        CajaChica::create([
            'nombre' => $request->input('nombreGasto'),
            'monto' => $request->input('montoCaja'),
        ]);
    }

    public function updateSaldoCajaChica(Request $request)
    {
        [$fechaInicio, $fechaFin] = getWeekIntervalNumber();
        $registros = CajaChicaSaldo::whereDate('fechaInicio', '>=', $fechaInicio)
            ->whereDate('fechaFin', '<=', $fechaFin)
            ->get();
        $saldo = $request->saldo;
        if (strpos($request->saldo, 'S/') !== false) {
            $saldo = explode('S/', $request->saldo);
            $saldo = $saldo[1];
        }
        if ($registros->isEmpty()) {
            $cajaChica = new CajaChicaSaldo();
            $cajaChica->saldo = $saldo;
            $cajaChica->fechaInicio = $fechaInicio;
            $cajaChica->fechaFin = $fechaFin;
            $cajaChica->save();
            return true;
        }
        foreach ($registros as $registro) {
            $registro->saldo = $saldo;
            $registro->save();
        }
    }

    public function getDescuentos()
    {
        [$startDate, $endDate] = getWeekIntervalNumber();
        $descuentos = Descuento::whereDate('creado_el', '>=', $startDate)
            ->whereDate('creado_el', '<=', $endDate)
            ->get();
        $data = $this->generateData($descuentos);
        return response()->json($data);
    }

    public function generateData($descuentos)
    {
        $usuarios = Usuario::where('rol', 2)->get()->keyBy('id');

        $data = $usuarios->map(function ($usuario) use ($descuentos) {
            $descuento = $descuentos->firstWhere('id_asesor', $usuario->id);
            if ($descuento) {
                return [
                    'idDescuento' => $descuento->id,
                    'idUsuario' => $usuario->id,
                    'usuario' => $usuario->usuario,
                    'envases' => $descuento->envases ?? 0,
                    'panos' => $descuento->panos ?? 0,
                    'pendiente' => $descuento->pendiente ?? 0,
                    'tardanza' => $descuento->tardanza ?? 0,
                    'total' => $descuento->total ?? 0,
                    'parches' => $descuento->parches ?? 0,
                ];
            }
            return [
                'idDescuento' => null,
                'idUsuario' => $usuario->id,
                'usuario' => $usuario->usuario,
                'envases' => 0,
                'panos' => 0,
                'pendiente' => 0,
                'tardanza' => 0,
                'total' => 0,
                'parches' => 0,
            ];
        });

        return $data->values()->all();
    }

    public function updateDescuento(Request $request)
    {
        $idUsuario = $request->input('idUsuario');
        $field = $request->input('field');
        $value = $request->input('value');
        $idDescuento = $request->input('idDescuento');
        if (trim($idDescuento) == "") {
            $descuento = new Descuento();
            $descuento->id_asesor = $idUsuario;
            $descuento->$field = $value;
            $descuento->save();
            return response()->json(['success' => true]);
        }
        // Actualizar el modelo
        $descuento = Descuento::find($idDescuento);
        $descuento->$field = $value;
        $descuento->save();

        return response()->json(['success' => true]);
    }

    public function getVentasOficina()
    {
        $productos = Producto::all();
        $chunks = $productos->chunk(ceil($productos->count() / 2));
        $productos1 = $chunks->get(0);
        $productos2 = $chunks->get(1);
        // Obtener todas las ventas que son de oficina
        $ventas = Venta::with(['ventaDetalles', 'pagos' => function ($query) {
            $query->orderBy('creado_el', 'desc'); // Ordena los pagos por fecha de creación descendente
        }])
            ->where('es_oficina', 1)
            ->get();

        $ventasOficina = $ventas->map(function ($venta) {
            // Calcular el total de productos
            $totalProductos = $venta->ventaDetalles->sum('cantidad');

            // Obtener el último pago
            $ultimoPago = $venta->pagos->first();
            $abono = $ultimoPago->abono ?? 0;
            $pendiente = $venta->pagos->last()->pendiente;

            return [
                'idVenta' => $venta->id,
                'cliente' => $venta->nombre,
                'fecha' => $venta->fecha,
                'productos' => $totalProductos,
                'abono' => $abono,
                'pendiente' => $pendiente
            ];
        });

        return response()->json(['productos1' => $productos1, 'productos2' => $productos2, 'ventasOficina' => $ventasOficina]);
    }
    public function saveVentasOficina(Request $request)
    {
        Carbon::setLocale('es');

        $request->validate([
            'cliente' => 'required|string|max:255',
            'detalle' => 'required|array',
            'detalle.*.id' => 'required|integer|exists:productos,id',
            'detalle.*.cantidad' => 'required|numeric|min:1',
            'detalle.*.precio' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {
            // Crear la venta
            $venta = new Venta();
            $venta->id_usuario = Session::get('usuario_id');
            $venta->nombre = $request->cliente;
            $venta->es_oficina = 1;
            $venta->save();
            $insertedId = $venta->id;

            $total = 0;
            $totalProductos = 0;
            // Insertar detalles de la venta
            foreach ($request->detalle as $detalle) {
                DB::insert(
                    'insert into venta_detalle (id_venta, id_producto, cantidad, precio) values (?, ?, ?, ?)',
                    [$insertedId, $detalle["id"], $detalle["cantidad"], $detalle["precio"]]
                );
                $total += floatval($detalle["cantidad"]) * floatval($detalle["precio"]);
                $totalProductos += floatval($detalle["cantidad"]);
            }
            $pagado = floatval($request->abono) == $total;

            $venta->estado = $pagado ? 1 : 0;
            $venta->save();
            $pendiente = $pagado ? 0 : $total - floatval($request->abono);
            $pago = new Pago();
            $pago->id_cobrador = Session::get('usuario_id');
            $pago->id_venta = $insertedId;
            $pago->abono = $request->abono;
            $pago->pendiente = $pendiente;
            $pago->creado_el = Carbon::now()->translatedFormat('l, d \d\e F \d\e Y');
            $pago->save();

            DB::commit();

            return response()->json([
                'idVenta' => $venta->id,
                'cliente' => $venta->nombre,
                'fecha' => Carbon::now()->translatedFormat('l, d \d\e F \d\e Y'),
                'productos' => $totalProductos,
                'abono' => $request->abono,
                'pendiente' => $pendiente
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getVentasOficinaDetalle(Request $request)
    {
        $venta = Venta::find($request->idVenta);
        $detalle = DB::select("SELECT nombre,vd.precio,vd.cantidad 
                                FROM venta_detalle vd INNER JOIN productos p ON p.id = vd.id_producto 
                                WHERE vd.id_venta = {$venta->id} and vd.cantidad != 0");
        $pagos = Pago::where('id_venta', $venta->id)->get();
        return response()->json(["venta" => $venta, 'ventaDetalle' => $detalle, 'pagos' => $pagos]);
    }

    public function getComision()
    {
        [$startDate, $endDate] = getWeekIntervalNumber();
        $comisiones = Comision::whereDate('fecha', '>=', $startDate)
            ->whereDate('fecha', '<=', $endDate)
            ->get();
        $data = $this->generateDataComision($comisiones);
        return response()->json($data);
    }

    public function generateDataComision($comisiones)
    {
        $usuarios = Usuario::where('rol', 2)->get()->keyBy('id');

        $data = $usuarios->map(function ($usuario) use ($comisiones) {
            $comision = $comisiones->firstWhere('id_asesor', $usuario->id);
            if ($comision) {
                return [
                    'idComision' => $comision->id,
                    'idUsuario' => $usuario->id,
                    'usuario' => $usuario->usuario,
                    'productos' => $comision->productos ?? 0,
                    'vendedor' => $comision->vendedor ?? 0,
                    'lider' => $comision->lider ?? 0,
                    'comision' => $comision->comision ?? 0,
                    'descuentos' => $comision->descuentos ?? 0,
                    'bono' => $comision->bono ?? 0,
                    'pefectivo' => $comision->pefectivo ?? 0,
                    'pdeposito' => $comision->pdeposito ?? 0,
                    'pfinal' => $comision->pfinal ?? 0,
                    'fecha' => $comision->fecha ?? 0,
                ];
            }
            return [
                'idComision' => null,
                'idUsuario' => $usuario->id,
                'usuario' => $usuario->usuario,
                'productos' => 0,
                'vendedor' => 0,
                'lider' => 0,
                'comision' => 0,
                'descuentos' => 0,
                'bono' => 0,
                'pefectivo' => 0,
                'pdeposito' => 0,
                'pfinal' => 0,
                'fecha' => 0,
            ];
        });

        return $data->values()->all();
    }

    public function updateComision(Request $request)
    {
        $idUsuario = $request->input('idUsuario');
        $field = $request->input('field');
        $value = $request->input('value');
        $idcomision = $request->input('idcomision');
        if (trim($idcomision) == "") {
            $comision = new Comision();
            $comision->id_asesor = $idUsuario;
            $comision->$field = $value;
            $comision->save();
            return response()->json(['success' => true]);
        }
        // Actualizar el modelo
        $comision = Comision::find($idcomision);
        $comision->$field = $value;
        $comision->save();

        return response()->json(['success' => true]);
    }

    public function filtrarGrupos(Request $request)
    {
        $fecha = $request->fecha;
        $grupos = Grupo::whereDate('fecha', $fecha)->get();

        return response()->json($grupos);
    }

    public function asignarCobrador(Request $request)
{
    $grupoId = $request->input('grupo_id');
    $cobradorId = $request->input('cobrador_id');
    DB::table('grupo_usuario')->insertGetId([
        'id_grupo' => $grupoId,
        'id_usuario' => $cobradorId,
        'lider' => 0
    ]);
    return response()->json(['message' => 'Cobrador asignado correctamente']);
}
}
