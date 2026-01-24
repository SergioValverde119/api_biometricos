<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{
    // Muestra la lista de dispositivos en un componente Vue
    public function Index()
    {
        return Inertia::render('Devices/Index', [
            'devices' => Device::orderBy('created_at', 'desc')->get(),
            'total_online' => Device::whereNotNull('online')->count()
        ]);
    }

    // Página de reporte de asistencia
    public function Attendance()
    {
        return Inertia::render('Devices/Attendance', [
            // Mandamos los datos paginados para que Vue los maneje fácil
            'attendances' => Attendance::with('employee')
                ->orderBy('timestamp', 'desc')
                ->paginate(15)
        ]);
    }

    // Ver los logs de comunicación del hardware
    public function DeviceLog()
    {
        return Inertia::render('Devices/Logs', [
            'logs' => DB::table('device_log')->orderBy('created_at', 'desc')->paginate(20),
            'type' => 'device'
        ]);
    }
}