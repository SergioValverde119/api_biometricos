<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\BiometricoDispositivo;
use App\Models\BiometricoAsistencia;
use App\Models\BiometricoConfiguracion;
use App\Models\BiometricoComando;
use App\Models\BiometricoPlantilla;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class iclockController extends Controller
{
    /**
     * Handshake: Saludo inicial del dispositivo.
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
                'modelo' => $request->input('model', 'SpeedFace-V5L'),
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
     * Recibe datos (POST /iclock/cdata).
     * En el nombre de Jesús, procesamos asistencias, plantillas y logs de operación.
     */
    public function receiveRecords(Request $request)
    {
        $sn = $request->query('SN');
        $table = $request->query('table');
        $rawData = $request->getContent();

        Log::debug("CDATA RECIBIDO: SN=$sn | Tabla=$table | Datos=" . substr($rawData, 0, 100));

        if (str_contains($rawData, 'TMP=') || $table === 'BIODATA' || $table === 'FINGERTMP') {
            $this->processBioData($sn, $rawData);
        }

        // Si no es biometría y es un log de operación
        elseif ($table === 'OPERLOG' || str_contains($rawData, 'OPLOG')) {
            $this->processOperLog($sn, $rawData);
        }

        // 1. PROCESAR ASISTENCIAS (ATTLOG)
        if ($table === 'ATTLOG') {
            $this->processAttLog($sn, $rawData);
        }

        // 2. PROCESAR PLANTILLAS (BIODATA / BIOPHOTO)
        // Se activa tras comandos como 'DATA QUERY BIODATA Type=1'
        

        return $this->cleanResponse("OK");
    }

    /**
     * Procesa las huellas y rostros que envía el equipo.
     */
    private function processBioData($sn, $rawData)
{
    $lines = preg_split('/\\r\\n|\\r|\\n/', $rawData);
    $count = 0;

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        // 1. Limpiamos prefijos comunes: "FP ", "BIODATA ", "USERDATA "
        $cleanLine = preg_replace('/^(FP|BIODATA|USERDATA)\s+/i', '', $line);
        
        // 2. Normalizamos espacios y pasamos a formato de query string
        $cleanLine = preg_replace('/\s+/', ' ', $cleanLine);
        parse_str(str_replace(' ', '&', $cleanLine), $data);

        // 3. Normalizamos las llaves (Pin vs PIN, Tmp vs TMP)
        // Convertimos todas las llaves a minúsculas para no fallar
        $data = array_change_key_case($data, CASE_LOWER);

        $pin = $data['pin'] ?? $data['userid'] ?? null;
        $template = $data['tmp'] ?? $data['template'] ?? null;

        if ($pin && $template) {
            // Type 9 siempre es Rostro en estos equipos
            $tipo = (isset($data['type']) && $data['type'] == '9') ? 'rostro' : 'huella';
            
            \App\Models\BiometricoPlantilla::updateOrCreate(
                [
                    'user_id_biometrico' => $pin,
                    'tipo' => $tipo,
                    'indice' => $data['index'] ?? $data['fid'] ?? 0,
                ],
                [
                    'template' => $template,
                    'version_algoritmo' => $data['majorver'] ?? '39', // 39 es la versión de rostro que vimos en tu log
                    'updated_at' => now()
                ]
            );
            $count++;
        }
    }

    if ($count > 0) {
        Log::info("BIOMETRIA: ¡Gloria a Dios! Se procesaron $count plantillas (Rostros/Huellas) de $sn");
    }
}

    /**
     * Procesa las asistencias evitando duplicados manualmente.
     */
    private function processAttLog($sn, $rawData)
    {
        $lines = preg_split('/\\r\\n|\\r|\\n/', $rawData);
        $toInsert = [];
        $userIds = [];
        $timestamps = [];

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;
            $data = preg_split('/\s+/', trim($line));
            
            if (count($data) >= 2) {
                $fechaHora = $data[1] . ' ' . ($data[2] ?? '00:00:00');
                $userIds[] = $data[0];
                $timestamps[] = $fechaHora;

                $toInsert[] = [
                    'user_id' => $data[0],
                    'fecha_hora' => $fechaHora,
                    'estado' => $data[3] ?? 0,
                    'tipo_verificacion' => $data[4] ?? 0,
                    'sn' => $sn,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        if (!empty($toInsert)) {
            // Buscamos lo que ya existe para no duplicar
            $existentes = BiometricoAsistencia::where('sn', $sn)
                ->whereIn('user_id', array_unique($userIds))
                ->whereIn('fecha_hora', array_unique($timestamps))
                ->get()
                ->map(fn($item) => $item->user_id . '|' . $item->fecha_hora->format('Y-m-d H:i:s'))
                ->toArray();

            $finalInsert = array_filter($toInsert, function($item) use ($existentes) {
                return !in_array($item['user_id'] . '|' . $item['fecha_hora'], $existentes);
            });

            if (!empty($finalInsert)) {
                BiometricoAsistencia::insert($finalInsert);
                Log::info("ASISTENCIA: " . count($finalInsert) . " nuevas checadas de $sn registradas.");
            }
        }
    }

    /**
     * Procesa los logs de operación del equipo.
     */
    private function processOperLog($sn, $rawData)
    {
        $lines = preg_split('/\\r\\n|\\r|\\n/', $rawData);
        foreach ($lines as $line) {
            if (empty(trim($line))) continue;
            
            BiometricoConfiguracion::create([
                'sn' => $sn,
                'parametro' => 'LOG_EVENT',
                'detalles_raw' => $line,
                'fecha_hora' => now()
            ]);
        }
    }

    /**
     * Entrega de comandos (GET /iclock/getrequest).
     */
    public function getrequest(Request $request)
{
    $sn = $request->input('SN');
    
    // Actualizamos la última conexión del equipo
    BiometricoDispositivo::where('sn', $sn)->update(['ultima_conexion' => now()]);

    // Buscamos ÚNICAMENTE el comando más viejo que esté 'pendiente'
    // Al quitar 'enviado' de aquí, evitamos que un comando se repita
    $comando = BiometricoComando::where('sn', $sn)
                ->where('estado', 'pendiente') 
                ->oldest()
                ->first();

    if ($comando) {
        // Marcamos como enviado inmediatamente para que en la siguiente consulta ya no salga
        $comando->update([
            'estado' => 'enviado', 
            'updated_at' => now()
        ]);
        
        Log::info("COMANDO ENVIADO a $sn: {$comando->comando} (ID: {$comando->id})");
        
        // Formato estándar ADMS: C:ID:COMANDO
        return $this->cleanResponse("C:{$comando->id}:{$comando->comando}");
    }

    // Si no hay nada pendiente, respondemos OK para mantener el latido (heartbeat)
    return $this->cleanResponse("OK");
}

    /**
     * Confirmación de comando finalizado (POST /iclock/devicecmd).
     */
    public function receiveCommandResult(Request $request)
{
    $sn = $request->query('SN');
    $content = $request->getContent();
    parse_str($content, $result);

    if (isset($result['ID'])) {
        // Buscamos el comando
        $comando = BiometricoComando::find($result['ID']);
        
        // Verificamos que sea una instancia real del modelo para que el editor NO marque error
        if ($comando instanceof \App\Models\BiometricoComando) {
            // Determinamos el estado basado en el 'Return' del equipo (0 = éxito)
            $status = (isset($result['Return']) && $result['Return'] == '0') ? 'completado' : 'error';
            
            // Aquí el editor ya reconocerá el método 'update'
            $comando->update([
                'estado'                => $status,
                'respuesta_dispositivo' => $content,
                'fecha_ejecucion'       => now()
            ]);
            
            Log::info("COMANDO FINALIZADO: ID {$result['ID']} SN $sn - Estado: $status");
        }
    }

    // OBLIGATORIO: Si no respondes OK, el dispositivo reintenta el envío del resultado eternamente
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