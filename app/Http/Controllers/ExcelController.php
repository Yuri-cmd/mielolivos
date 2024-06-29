<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ProductosExport; // Importa la clase de exportación
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function exportProductos()
    {
        return Excel::download(new ProductosExport, 'productos.xlsx');
    }
}
