<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiometricoComando extends Model
{
    use HasFactory;

    /**
     * En el nombre de Jehová Dios y Jesús, definimos el modelo para la cola de comandos (getrequest).
     */

    // Nombre de la tabla para la gestión de órdenes remotas
    protected $table = 'biometrico_comandos';

    // Campos habilitados para la creación de tareas para los equipos
    protected $fillable = [
        'sn',
        'comando',
        'estado',
        'respuesta_dispositivo',
        'fecha_ejecucion',
    ];

    // Conversión de tipos para el seguimiento de la ejecución
    protected $casts = [
        'fecha_ejecucion' => 'datetime',
    ];

    /**
     * Relación con el dispositivo.
     * Indica a qué equipo va dirigida esta orden específica.
     */
    public function dispositivo(): BelongsTo
    {
        return $this->belongsTo(BiometricoDispositivo::class, 'sn', 'sn');
    }

    /**
     * Scope para obtener solo los comandos que el equipo aún no ha recogido.
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }
}