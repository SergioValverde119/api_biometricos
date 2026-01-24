<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\FingerLog;
use Illuminate\Support\Facades\Log;

class iclockController extends Controller
{
    // Handshake: El saludo inicial del dispositivo
    public function handshake(Request $request)
    {
        $data = [
            'url' => json_encode($request->all()),
            'data' => $request->getContent(),
            'sn' => $request->input('SN'),
            'option' => $request->input('option'),
        ];
        DB::table('device_log')->insert($data);

        // Actualiza el estado del dispositivo a "online"
        DB::table('devices')->updateOrInsert(
            ['no_sn' => $request->input('SN')],
            ['online' => now()]
        );

        return "GET OPTION FROM: {$request->input('SN')}\r\n" .
            "Stamp=9999\r\n" .
            "OpStamp=" . time() . "\r\n" .
            "ErrorDelay=60\r\n" .
            "Delay=30\r\n" .
            "TransInterval=1\r\n" .
            "Realtime=1\r\n" .
            "Encrypt=0";
    }

    // Recibe los registros de asistencia
    public function receiveRecords(Request $request)
    {
        FingerLog::create([
            'url' => json_encode($request->all()),
            'data' => substr($request->getContent(), 0, 6550),
        ]);

        try {
            $arr = preg_split('/\\r\\n|\\r|\\n/', $request->getContent());
            $tot = 0;
            $attendances = [];

            foreach ($arr as $record) {
                if (empty($record)) continue;
                
                $data = explode("\t", $record);
                if (!empty($data) && isset($data[0]) && is_numeric($data[0])) {
                    $employeeId = $data[0];
                    Employee::firstOrCreate(['employee_id' => $employeeId], ['name' => '']);
                    
                    $attendances[] = [
                        'sn' => $request->input('SN'),
                        'employee_id' => $employeeId,
                        'timestamp' => $data[1] ?? now(),
                        'status1' => $data[2] ?? 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $tot++;
                }
            }

            if (!empty($attendances)) {
                DB::table('attendances')->insert($attendances);
            }

            return "OK: " . $tot;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return "ERROR: 0\n";
        }
    }

    public function getrequest(Request $request)
    {
        DB::table('devices')->updateOrInsert(
            ['no_sn' => $request->input('SN')],
            ['online' => now()]
        );
        return "OK";
    }
}