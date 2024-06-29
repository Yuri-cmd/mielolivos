<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Producto;
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
        $grupos = DB::table('grupos')->selectRaw('id,nombre,DATE(fecha) as fecha')->whereDate('fecha', $today)->get();
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

        return response()->json(['success' => true, 'message' => 'Datos recibidos correctamente', 'id' => $insertedId]);
    }

    public function deleteGrupo(Request $request)
    {
        $deleted = DB::table('grupos')->where('id', $request->input('id'))->delete();
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

    public function createDetalleGrupo(Request $request)
    {
        $data = json_decode($request->data);
        $insertedId = DB::table('grupo_usuario')->insertGetId([
            'id_grupo' => $request->input('idGrupo'),
            'id_usuario' => $request->input('idUsuario')
        ]);
        foreach ($data as $d) {
            $id = str_replace('stock-', '', $d->stock_id);
            DB::insert(
                'insert into grupo_usuario_producto (id_grupo_usuario,id_producto,stock) values (?,?,?)',
                [$insertedId, $id, "{$d->input_value}"]
            );

            $producto = Producto::findOrFail($id);
            $producto->stock = ($d->stock_value - $d->input_value);

            $producto->save();
        }
    }

    public function saveDetalle(Request $request)
    {
        DB::table('grupos')
            ->where('id', $request->id)
            ->update([
                'deposito' => $request->depo,
                'taxi' => $request->taxi,
                'efectivo' => $request->efectivo,
            ]);
    }

    public function getGrupoDetalle(Request $request)
    {
        $datoGrupo = DB::table('grupos')->where('id', $request->input('id'))->get();
        $detalle = DB::select("SELECT
                        gu.id,
                        u.usuario,
                        SUM(stock) AS campo,
                        SUM(vendido) AS vendidos,
                        (SUM(stock) - SUM(vendido)) AS sobrantes
                    FROM
                        grupo_usuario gu
                        INNER JOIN grupo_usuario_producto gp ON gp.id_grupo_usuario = gu.id
                        INNER JOIN usuario u ON u.id = gu.id_usuario 
                    WHERE
                        id_grupo = {$request->input('id')} 
                    GROUP BY
                        gu.id, u.usuario");
        return json_encode(["datoGrupo" => $datoGrupo, "detalle" => $detalle]);
    }
}
