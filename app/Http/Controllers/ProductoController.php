<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\ProductosImport;
use App\Models\GrupoUsuario;
use App\Models\GrupoUsuarioProducto;
use App\Models\Master;
use Maatwebsite\Excel\Facades\Excel;

class ProductoController extends Controller
{
    public function getProducto()
    {
        $productos = DB::table('productos')->where('estado', 1)->get();
        return response()->json($productos);
    }

    public function updateEstadoProducto(Request $request)
    {
        $ids = $request->input('ids');
        DB::table('productos')->whereIn('id', $ids)->update(['estado' => 0]);
        return response()->json(['success' => 'Productos actualizados correctamente.']);
    }

    public function updateStockProducto(Request $request)
    {
        $producto = DB::table('productos')->where('id', $request->id)->first();
        $stock = $producto->stock + $request->stock;
        DB::table('productos')->where('id', $request->id)->update(['stock' => $stock]);
    }

    public function update(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'id' => 'required|integer',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'precio1' => 'required|numeric',
            'precio2' => 'nullable|numeric',
            'stock' => 'required|integer',
            'proveedor' => 'required|string|max:255',
        ]);

        try {
            // Obtener el producto por su ID
            $producto = Producto::findOrFail($request->id);

            // Actualizar los campos del producto
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->precio1 = $request->precio1;
            $producto->precio2 = $request->precio2;
            $producto->stock = $request->stock;
            $producto->proveedor = $request->proveedor;

            // Guardar los cambios en la base de datos
            $producto->save();
            // Retornar una respuesta JSON de éxito
            return response()->json(['success' => true, 'message' => 'Producto actualizado correctamente']);
        } catch (\Exception $e) {
            // Retornar una respuesta JSON de error
            return response()->json(['success' => false, 'message' => 'Error al actualizar el producto: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'precio1' => 'required|numeric',
            'precio2' => 'nullable|numeric',
            'stock' => 'required|integer',
            'proveedor' => 'required|string|max:255',
        ]);

        try {
            // Crear un nuevo producto con los datos recibidos
            $producto = new Producto();
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->precio1 = $request->precio1;
            $producto->precio2 = $request->precio2;
            $producto->stock = $request->stock;
            $producto->proveedor = $request->proveedor;
            $producto->save();

            // Retornar una respuesta JSON de éxito
            return response()->json(['success' => true, 'message' => 'Producto creado correctamente']);
        } catch (\Exception $e) {
            // Retornar una respuesta JSON de error
            return response()->json(['success' => false, 'message' => 'Error al crear el producto: ' . $e->getMessage()], 500);
        }
    }

    public function importar(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new ProductosImport, $request->file('file'));

        return redirect()->back()->with('success', 'Productos importados correctamente.');
    }

    public function getProductoMaster(Request $request)
    {
        $results = GrupoUsuarioProducto::where('id_grupo_usuario', $request->idUser)
            ->with('producto:id,nombre')
            ->get(['id', 'id_producto', 'stock']);
        
        // Formatear los resultados
        $formattedResults = $results->map(function ($master) {
            return [
                'id' => $master->producto->id,
                'nombre' => $master->producto->nombre,
                'cantidad' => $master->stock,
            ];
        });
        return response()->json($formattedResults);
    }
}
