<?php

namespace App\Imports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductosImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Validar y guardar productos
        $producto = Producto::updateOrCreate(
            ['id' => $row['id']], // Buscar por ID
            [
                'nombre' => $row['nombre'],
                'descripcion' => $row['descripcion'],
                'precio1' => $row['precio1'],
                'precio2' => $row['precio2'],
                'stock' => $row['stock'],
                'proveedor' => $row['proveedor'],
                'estado' => $row['estado'], // Suponiendo que 1 representa activo
            ]
        );

        return $producto;
    }
}
