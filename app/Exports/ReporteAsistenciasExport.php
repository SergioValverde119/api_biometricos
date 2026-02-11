<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReporteAsistenciasExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithMapping, WithCustomStartCell, WithEvents
{
    protected $datos;

    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    public function collection()
    {
        return $this->datos;
    }

    /**
     * En el nombre de Jesús, movemos la tabla a la fila 8 
     * para que quepan todos los renglones de información arriba.
     */
    public function startCell(): string
    {
        return 'A8';
    }

    public function map($row): array
    {
        $tipoTexto = match ((int)$row->tipo_verificacion) {
            1 => 'Huella',
            15 => 'Rostro',
            3 => 'Contraseña',
            4 => 'Tarjeta',
            default => 'Otro (' . $row->tipo_verificacion . ')',
        };

        return [
            $row->user_id,
            $row->fecha_hora instanceof \Carbon\Carbon ? $row->fecha_hora->format('Y-m-d') : substr($row->fecha_hora, 0, 10),
            $row->fecha_hora instanceof \Carbon\Carbon ? $row->fecha_hora->format('H:i:s') : substr($row->fecha_hora, 11, 8),
            $tipoTexto,
            $row->dispositivo ? $row->dispositivo->nombre : $row->sn,
        ];
    }

    public function headings(): array
    {
        return [
            'ID Empleado',
            'Fecha',
            'Hora',
            'Método',
            'Dispositivo',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Ahora los encabezados están en la fila 8 y son gris oscuro con blanco
            8 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F2937'] // Gris oscuro original (gray-800)
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * Lógica para convertir los días del horario en texto amigable.
     */
    private function formatearDias($diasRaw)
    {
        if (!$diasRaw) return 'No especificado';
        $dias = strtolower($diasRaw);
        
        if (str_contains($dias, 'viernes')) return 'Lunes a Viernes';
        if (str_contains($dias, 'sábado') || str_contains($dias, 'sabado')) return 'Sábado y Domingo';
        
        return $diasRaw;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $primerRegistro = $this->datos->first();
                
                if ($primerRegistro) {
                    $empleado = $primerRegistro->empleado;
                    $horario = $empleado ? $empleado->schedule : null;

                    // 1. Título General: RESUMEN DEL EMPLEADO (Gris oscuro con Blanco)
                    $sheet->mergeCells('A1:B1');
                    $sheet->setCellValue('A1', 'RESUMEN DEL EMPLEADO');
                    $sheet->getStyle('A1:B1')->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '1F2937'] // Aplicado el mismo gris oscuro
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                    // 2. Número de Empleado
                    $sheet->setCellValue('A2', 'Número de Empleado:');
                    $sheet->setCellValue('B2', $primerRegistro->user_id);

                    // 3. Nombre Completo
                    $sheet->setCellValue('A3', 'Nombre:');
                    $nombreCompleto = $empleado ? "{$empleado->name} {$empleado->last_name}" : 'No registrado';
                    $sheet->setCellValue('B3', $nombreCompleto);

                    // 4. Días Laborables
                    $sheet->setCellValue('A4', 'Días Laborables:');
                    $diasTexto = $horario ? $this->formatearDias($horario->day_of_week) : 'Sin asignar';
                    $sheet->setCellValue('B4', $diasTexto);

                    // 5. Hora de Entrada
                    $sheet->setCellValue('A5', 'Entrada Programada:');
                    $sheet->setCellValue('B5', $horario ? $horario->entry_time : '--');

                    // 6. Hora de Salida
                    $sheet->setCellValue('A6', 'Salida Programada:');
                    $sheet->setCellValue('B6', $horario ? $horario->exit_time : '--');

                    // Estilos para las etiquetas de la columna A
                    $sheet->getStyle('A2:A6')->getFont()->setBold(true);
                    $sheet->getStyle('A2:A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    // Borde al bloque de información
                    $sheet->getStyle('A1:B6')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => ['rgb' => '1F2937'],
                            ],
                        ],
                    ]);
                }
            },
        ];
    }
}