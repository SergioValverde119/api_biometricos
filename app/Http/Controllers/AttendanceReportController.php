<?php

namespace App\Http\Controllers;

use App\Models\BiometricoAsistencia;
use App\Exports\ReporteAsistenciasExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

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

        if (!$request->has('codigo_empleado')) {
        return redirect()->route('home');
    }
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

    public function exportar(Request $request)
    {
        // 1. Recibir filtros del Request
        $userId = $request->input('user_id');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        // 2. Construir la consulta (Misma lógica que la búsqueda)
        $query = BiometricoAsistencia::query();

        if ($userId) {
            $query->where('user_id', 'like', "%{$userId}%");
        }

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha_hora', [
                $fechaInicio . ' 00:00:00', 
                $fechaFin . ' 23:59:59'
            ]);
        } else {
            // Por defecto, si no hay fecha, limitamos para no descargar todo el historial
            // O puedes poner las del mes actual:
            $query->whereMonth('fecha_hora', Carbon::now()->month);
        }

        // 3. Ejecutar consulta (Sin paginar, traemos todo)
        $datos = $query->orderBy('fecha_hora', 'DESC')->get();

        // 4. Generar nombre del archivo
        $nombreArchivo = 'Reporte_Asistencias_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        // 5. Descargar
        return Excel::download(new ReporteAsistenciasExport($datos), $nombreArchivo);
    }
}