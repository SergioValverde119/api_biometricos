<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReporteAsistenciasExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithMapping
{
    protected $datos;

    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    /**
     * Retorna la colección de datos.
     */
    public function collection()
    {
        return $this->datos;
    }

    /**
     * Mapea cada fila para darle formato legible antes de escribir en el Excel.
     */
    public function map($row): array
    {
        // Traducir tipo de verificación
        $tipoTexto = match ((int)$row->tipo_verificacion) {
            1 => 'Huella',
            15 => 'Rostro',
            3 => 'Contraseña',
            4 => 'Tarjeta',
            default => $row->tipo_verificacion,
        };

        return [
            $row->user_id,
            // Separamos Fecha y Hora en columnas distintas
            $row->fecha_hora instanceof \Carbon\Carbon ? $row->fecha_hora->format('Y-m-d') : substr($row->fecha_hora, 0, 10),
            $row->fecha_hora instanceof \Carbon\Carbon ? $row->fecha_hora->format('H:i:s') : substr($row->fecha_hora, 11, 8),
            // Eliminamos la columna Estado
            $tipoTexto,
            $row->sn, // Número de serie o Alias del dispositivo
        ];
    }

    /**
     * Define los títulos de las columnas.
     */
    public function headings(): array
    {
        return [
            'ID Empleado',
            'Fecha',      // Columna independiente
            'Hora',       // Columna independiente
            'Método',
            'Dispositivo',
        ];
    }

    /**
     * Estilos visuales (Encabezado oscuro).
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Fila 1: Negrita, Fondo Gris Oscuro, Texto Blanco, Centrado
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F2937'] // Gris oscuro (Tailwind gray-800)
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}