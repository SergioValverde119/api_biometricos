<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\BiometricoDispositivo;
use App\Models\BiometricoAsistencia;
use App\Models\BiometricoConfiguracion;
use App\Models\BiometricoComando;
use Illuminate\Support\Facades\DB;

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

        if ($table === 'ATTLOG') {
            $lines = preg_split('/\\r\\n|\\r|\\n/', $rawData);
            $toInsert = [];
            $userIds = [];
            $timestamps = [];

            foreach ($lines as $line) {
                if (empty(trim($line))) continue;
                $data = explode("\t", $line);

                if (isset($data[0]) && isset($data[1])) {
                    $userIds[] = $data[0];
                    $timestamps[] = $data[1];
                    $toInsert[] = [
                        'user_id' => $data[0],
                        'fecha_hora' => $data[1],
                        'estado' => $data[2] ?? 0,
                        'tipo_verificacion' => $data[3] ?? 0,
                        'sn' => $sn,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            if (!empty($toInsert)) {
                // Validación masiva de duplicados para optimizar el rendimiento
                $existentes = BiometricoAsistencia::where('sn', $sn)
                    ->whereIn('user_id', array_unique($userIds))
                    ->whereIn('fecha_hora', array_unique($timestamps))
                    ->get(['user_id', 'fecha_hora'])
                    ->map(fn($item) => $item->user_id . '|' . $item->fecha_hora)
                    ->toArray();

                $finalInsert = array_filter($toInsert, function($item) use ($existentes) {
                    return !in_array($item['user_id'] . '|' . $item['fecha_hora'], $existentes);
                });

                if (!empty($finalInsert)) {
                    BiometricoAsistencia::insert($finalInsert);
                }
            }
        }

        if ($table === 'OPERLOG') {
            $lines = preg_split('/\\r\\n|\\r|\\n/', $rawData);
            foreach ($lines as $line) {
                if (empty(trim($line))) continue;
                $data = explode("\t", $line);
                
                BiometricoConfiguracion::create([
                    'sn'           => $sn,
                    'parametro'    => $data[3] ?? 'CONFIG_CHANGE',
                    'user_id'      => $data[1] ?? null,
                    'fecha_hora'   => $data[2] ?? now(),
                    'detalles_raw' => $line
                ]);
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

        // Agregamos pista de tipo para el IDE
        /** @var BiometricoComando|null $comando */
        $comando = BiometricoComando::where('sn', $sn)
                    ->where('estado', 'pendiente')
                    ->oldest()
                    ->first();

        if ($comando) {
            $comando->estado = 'enviado';
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
            // Agregamos pista de tipo para evitar el error P1013 en save()
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

                Log::info("Comando {$comando->id} finalizado con estado: $nuevoEstado para SN: $sn");
            }
        }

        return $this->cleanResponse("OK");
    }

    /**
     * Limpieza de respuesta para protocolo ADMS.
     */
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