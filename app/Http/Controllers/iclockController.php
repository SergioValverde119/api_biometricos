<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\BiometricoDispositivo;
use App\Models\BiometricoAsistencia;
use App\Models\BiometricoConfiguracion;
use App\Models\BiometricoComando;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class iclockController extends Controller
{
    /**
     * Handshake: El saludo inicial del dispositivo.
     */
    public function handshake(Request $request)
    {
        $sn = $request->input('SN');

        BiometricoDispositivo::updateOrCreate(
            ['sn' => $sn],
            [
                'ip' => $request->ip(),
                'ultima_conexion' => now(),
                'activo' => true,
                'nombre' => $request->input('nombre', 'Dispositivo ' . $sn)
            ]
        );

        $content = "GET OPTION FROM: {$sn}\r\n" .
                   "Stamp=9999\r\n" .
                   "OpStamp=" . time() . "\r\n" .
                   "ErrorDelay=60\r\n" .
                   "Delay=30\r\n" .
                   "TransInterval=1\r\n" .
                   "Realtime=1\r\n" .
                   "Encrypt=0\r\n";

        return $this->cleanResponse($content);
    }

    /**
     * Recibe los registros de asistencia y configuraciones (POST /iclock/cdata).
     */
    public function receiveRecords(Request $request)
    {
        $sn = $request->query('SN');
        $table = $request->query('table');
        $rawData = $request->getContent();

        // Log para depuración en tiempo real
        Log::debug("DATA_RAW de $sn: " . substr($rawData, 0, 500));

        // 1. Procesamiento de ASISTENCIAS (ATTLOG)
        if ($table === 'ATTLOG') {
            $lines = preg_split('/\\r\\n|\\r|\\n/', $rawData);
            $toInsert = [];
            $userIds = [];
            $timestamps = [];

            foreach ($lines as $line) {
                if (empty(trim($line)) || str_starts_with($line, 'OPLOG')) continue;
                $data = preg_split('/\s+/', trim($line)); // Más robusto con espacios/tabs

                if (count($data) >= 2) {
                    $userIds[] = $data[0];
                    $timestamps[] = $data[1] . ' ' . ($data[2] ?? '00:00:00');
                    
                    $fechaFormateada = $data[1] . ' ' . ($data[2] ?? '00:00:00');

                    $toInsert[] = [
                        'user_id' => $data[0],
                        'fecha_hora' => $fechaFormateada,
                        'estado' => $data[3] ?? 0,
                        'tipo_verificacion' => $data[4] ?? 0,
                        'sn' => $sn,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            if (!empty($toInsert)) {
                $existentes = BiometricoAsistencia::where('sn', $sn)
                    ->whereIn('user_id', array_unique($userIds))
                    ->whereIn('fecha_hora', array_unique($timestamps))
                    ->get(['user_id', 'fecha_hora'])
                    ->map(fn($item) => $item->user_id . '|' . $item->fecha_hora->format('Y-m-d H:i:s'))
                    ->toArray();

                $finalInsert = array_filter($toInsert, function($item) use ($existentes) {
                    return !in_array($item['user_id'] . '|' . $item['fecha_hora'], $existentes);
                });

                if (!empty($finalInsert)) {
                    BiometricoAsistencia::insert($finalInsert);
                    Log::info("ASISTENCIA: " . count($finalInsert) . " nuevas de $sn");
                }
            }
        }

        // 2. Procesamiento de LOGS DE OPERACION (OPLOG)
        if ($table === 'OPERLOG' || $table === 'OPLOG' || str_contains($rawData, 'OPLOG')) {
            $lines = preg_split('/\\r\\n|\\r|\\n/', $rawData);
            $opsGuardadas = 0;

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || !str_starts_with($line, 'OPLOG')) continue;
                
                // Usamos regex para capturar los campos separados por tabs o múltiples espacios
                $data = preg_split('/\s+/', $line);
                
                if (count($data) >= 4) {
                    // En el nombre de Jesús, parseamos la fecha con cuidado
                    // Formato esperado: OPLOG [Type] [AdminID] [Fecha] [Hora] [Param] ...
                    $fechaHoraString = $data[3] . ' ' . ($data[4] ?? '00:00:00');
                    
                    try {
                        $fechaValida = Carbon::parse($fechaHoraString);
                        
                        // Si el parámetro está en la posición 5 (porque fecha y hora son 3 y 4)
                        $parametro = $data[5] ?? $data[1];

                        BiometricoConfiguracion::create([
                            'sn'           => $sn,
                            'parametro'    => $parametro,
                            'user_id'      => $data[2] ?? null,
                            'fecha_hora'   => $fechaValida,
                            'detalles_raw' => $line
                        ]);
                        $opsGuardadas++;
                    } catch (\Exception $e) {
                        Log::error("Error parseando fecha OPLOG: " . $fechaHoraString);
                    }
                }
            }
            if ($opsGuardadas > 0) {
                Log::info("OPERLOG: $opsGuardadas registros guardados para $sn");
            }
        }

        return $this->cleanResponse("OK");
    }

    /**
     * El equipo pregunta si hay órdenes pendientes (GET /iclock/getrequest).
     */
    public function getrequest(Request $request)
    {
        $sn = $request->input('SN');

        BiometricoDispositivo::where('sn', $sn)->update(['ultima_conexion' => now()]);

        /** @var BiometricoComando|null $comando */
        $comando = BiometricoComando::where('sn', $sn)
                    ->where(function($query) {
                        $query->where('estado', 'pendiente')
                              ->orWhere(function($q) {
                                  $q->where('estado', 'enviado')
                                    ->where('updated_at', '<', now()->subMinutes(15));
                              });
                    })
                    ->oldest()
                    ->first();

        if ($comando) {
            $comando->estado = 'enviado';
            $comando->updated_at = now(); 
            $comando->save();
            
            return $this->cleanResponse("C:{$comando->id}:{$comando->comando}");
        }

        return $this->cleanResponse("OK");
    }

    /**
     * El equipo informa el resultado de la ejecución de un comando (POST /iclock/devicecmd).
     */
    public function receiveCommandResult(Request $request)
    {
        $sn = $request->query('SN');
        $content = $request->getContent();
        parse_str($content, $result);

        if (isset($result['ID'])) {
            /** @var BiometricoComando|null $comando */
            $comando = BiometricoComando::find($result['ID']);
            
            if ($comando) {
                $nuevoEstado = (isset($result['Return']) && $result['Return'] == '0') 
                                ? 'completado' 
                                : 'error';

                $comando->estado = $nuevoEstado;
                $comando->respuesta_dispositivo = $content;
                $comando->fecha_ejecucion = now();
                $comando->save();
                
                Log::info("COMANDO FINALIZADO: ID {$comando->id} SN $sn Resultado: $nuevoEstado");
            }
        }

        return $this->cleanResponse("OK");
    }

    private function cleanResponse($content)
    {
        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Cache-Control', 'no-cache, private')
            ->header('Connection', 'close')
            ->withoutCookie('laravel_session')
            ->withoutCookie('XSRF-TOKEN');
    }
}