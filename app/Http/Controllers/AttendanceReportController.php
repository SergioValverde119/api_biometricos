<?php

namespace App\Http\Controllers;

use App\Models\BiometricoAsistencia;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceReportController extends Controller
{
    /**
     * Muestra la vista inicial del monitor de asistencia.
     * * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Reportes/AsistenciaCruda', [
            'checadas' => [],
            'filtros' => null
        ]);
    }

    /**
     * Busca los registros de asistencia basados en el ID del empleado y un rango de fechas.
     * Incluye la relación con el dispositivo para mostrar nombres/alias.
     * * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function buscar(Request $request)
    {
        // 1. Validamos los datos de entrada
        $request->validate([
            'codigo_empleado' => 'required|string',
            'fecha_inicio'    => 'required|date',
            'fecha_fin'       => 'required|date',
        ], [
            'codigo_empleado.required' => 'El ID del empleado es obligatorio.',
            'fecha_inicio.date'        => 'La fecha de inicio no es válida.',
            'fecha_fin.date'           => 'La fecha final no es válida.',
        ]);

        $idEmpleado = $request->input('codigo_empleado');
        $inicio = $request->input('fecha_inicio');
        $fin = $request->input('fecha_fin');

        // 2. Realizamos la consulta con Eager Loading ('dispositivo')
        // Esto evita el error SQL al separar correctamente el ID de las fechas
        $checadas = BiometricoAsistencia::with('dispositivo')
            ->where('user_id', $idEmpleado)
            ->whereBetween('fecha_hora', [
                $inicio . ' 00:00:00', 
                $fin . ' 23:59:59'
            ])
            ->orderBy('fecha_hora', 'DESC') // Mostramos lo más reciente arriba
            ->get();

        // 3. Devolvemos la respuesta a Inertia
        return Inertia::render('Reportes/AsistenciaCruda', [
            'checadas' => $checadas,
            'filtros'  => [
                'codigo_empleado' => $idEmpleado,
                'fecha_inicio'    => $inicio,
                'fecha_fin'       => $fin
            ]
        ]);
    }
}