<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\FingerLog;
use Illuminate\Support\Facades\Log;

class iclockController extends Controller
{
    /**
     * Handshake: El saludo inicial del dispositivo
     * Se activa cuando el equipo se enciende o conecta a la red.
     */
    public function handshake(Request $request)
    {
        $sn = $request->input('SN');
        
        // Log diario del saludo
        Log::channel('daily')->info("--- HANDSHAKE DETECTADO ---", [
            'sn' => $sn,
            'params' => $request->all()
        ]);

        DB::table('device_log')->insert([
            'url' => json_encode($request->all()),
            'data' => $request->getContent() ?: 'HANDSHAKE',
            'sn' => $sn,
            'option' => $request->input('option'),
            'created_at' => now()
        ]);

        DB::table('devices')->updateOrInsert(
            ['no_sn' => $sn],
            ['online' => now()]
        );

        // Respuesta estándar ADMS
        return "GET OPTION FROM: {$sn}\r\n" .
            "Stamp=9999\r\n" .
            "OpStamp=" . time() . "\r\n" .
            "ErrorDelay=60\r\n" .
            "Delay=30\r\n" .
            "TransInterval=1\r\n" .
            "Realtime=1\r\n" .
            "Encrypt=0";
    }

    /**
     * Recibe los registros de asistencia (POST /iclock/cdata)
     */
    public function receiveRecords(Request $request)
    {
        $sn = $request->query('SN');
        $rawData = $request->getContent();

        // 1. Guardar LOG CRUDO de inmediato para no perder nada
        Log::channel('daily')->debug("DATA_RAW de $sn: " . $rawData);

        // Registro en tabla de auditoría
        FingerLog::create([
            'url' => json_encode($request->all()),
            'data' => substr($rawData, 0, 6550),
        ]);

        try {
            $lines = preg_split('/\\r\\n|\\r|\\n/', $rawData);
            $totalProcessed = 0;
            $attendances = [];

            foreach ($lines as $line) {
                if (empty(trim($line))) continue;
                
                $data = explode("\t", $line);
                
                // Verificamos que sea una línea de asistencia válida (ID numérico)
                if (isset($data[0]) && is_numeric($data[0])) {
                    $employeeId = $data[0];
                    
                    // Asegurar que el empleado exista (con la ayuda de Dios)
                    Employee::firstOrCreate(
                        ['employee_id' => $employeeId], 
                        ['name' => 'NUEVO EMPLEADO']
                    );
                    
                    // Preparar insert para PostgreSQL
                    $attendances[] = [
                        'sn' => $sn,
                        'employee_id' => $employeeId,
                        'timestamp' => $data[1] ?? now(),
                        // Ajuste para PostgreSQL Boolean (0=false, 1=true)
                        'status1' => (isset($data[2]) && $data[2] == 1) ? true : false,
                        // Columnas obligatorias que descubrimos antes
                        'table' => 'adms_push',
                        'stamp' => time(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $totalProcessed++;
                }
            }

            if (!empty($attendances)) {
                DB::table('attendances')->insert($attendances);
            }

            return "OK"; // Respondemos OK simple para liberar el buffer del equipo

        } catch (\Exception $e) {
            // Si algo falla, lo guardamos en el log pero NO bloqueamos el equipo
            Log::channel('daily')->error("ERROR en receiveRecords ($sn): " . $e->getMessage());
            return "OK"; 
        }
    }

    /**
     * El equipo pregunta si hay órdenes pendientes
     */
    public function getrequest(Request $request)
    {
        $sn = $request->input('SN');

        // Log de actividad para monitoreo
        Log::channel('daily')->debug("PING de equipo: $sn");

        DB::table('devices')->updateOrInsert(
            ['no_sn' => $sn],
            ['online' => now()]
        );

        return "OK"; // Por ahora siempre OK hasta que implementemos comandos
    }
}