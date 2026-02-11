<?php

namespace App\Http\Controllers;

use App\Models\BiometricoAsistencia;
use App\Exports\ReporteAsistenciasExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceReportController extends Controller
{
    /**
     * Muestra la vista inicial del monitor de asistencia.
     */
    public function index()
    {
        return Inertia::render('Reportes/AsistenciaCruda', [
            'checadas' => [],
            'filtros' => null
        ]);
    }

    /**
     * Busca los registros. Soluciona el error de PostgreSQL casteando employee_id a texto.
     */
    public function buscar(Request $request)
    {
        if (!$request->has('codigo_empleado')) {
            return redirect()->route('home');
        }

        $request->validate([
            'codigo_empleado' => 'required|string',
            'fecha_inicio'    => 'required|date',
            'fecha_fin'       => 'required|date',
        ]);

        $busqueda = $request->input('codigo_empleado');
        $inicio = $request->input('fecha_inicio');
        $fin = $request->input('fecha_fin');

        // Construimos la consulta con Eager Loading de dispositivo, empleado y HORARIO
        $query = BiometricoAsistencia::with(['dispositivo', 'empleado.schedule']);

        if ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                $q->where('user_id', 'like', "%{$busqueda}%")
                  ->orWhereIn('user_id', function($sub) use ($busqueda) {
                      $sub->select(DB::raw('CAST(employee_id AS TEXT)'))
                          ->from('employees')
                          ->where('name', 'ILIKE', "%{$busqueda}%") 
                          ->orWhere('last_name', 'ILIKE', "%{$busqueda}%");
                  });
            });
        }

        $checadas = $query->whereBetween('fecha_hora', [
                $inicio . ' 00:00:00', 
                $fin . ' 23:59:59'
            ])
            ->orderBy('fecha_hora', 'DESC')
            ->get();

        return Inertia::render('Reportes/AsistenciaCruda', [
            'checadas' => $checadas,
            'filtros'  => [
                'codigo_empleado' => $busqueda,
                'fecha_inicio'    => $inicio,
                'fecha_fin'       => $fin
            ]
        ]);
    }

    public function exportar(Request $request)
    {
        $busqueda = $request->input('user_id') ?? $request->input('codigo_empleado');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $query = BiometricoAsistencia::with(['dispositivo', 'empleado.schedule']);

        if ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                $q->where('user_id', 'like', "%{$busqueda}%")
                  ->orWhereIn('user_id', function($sub) use ($busqueda) {
                      $sub->select(DB::raw('CAST(employee_id AS TEXT)'))
                          ->from('employees')
                          ->where('name', 'ILIKE', "%{$busqueda}%")
                          ->orWhere('last_name', 'ILIKE', "%{$busqueda}%");
                  });
            });
        }

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha_hora', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);
        }

        $datos = $query->orderBy('fecha_hora', 'DESC')->get();
        $nombreArchivo = 'Reporte_Asistencias_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ReporteAsistenciasExport($datos), $nombreArchivo);
    }
}