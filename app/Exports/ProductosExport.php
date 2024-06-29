<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductosExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function collection()
    {
        return Producto::all(['id', 'nombre', 'descripcion', 'precio1', 'precio2', 'stock', 'proveedor', 'estado']);
    }

    public function headings(): array
    {
        return [
            'id',
            'nombre',
            'descripcion',
            'precio1',
            'precio2',
            'stock',
            'proveedor',
            'estado',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // Ajustar automáticamente las columnas al contenido
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'alignment' => [
                'vertical' => 'center',
                'horizontal' => 'center',
            ],
        ]);

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
