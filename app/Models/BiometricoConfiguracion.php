<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiometricoConfiguracion extends Model
{
    use HasFactory;

    /**
     * En el nombre de Jehová Dios y Jesús, definimos el modelo para los ajustes técnicos del equipo.
     */

    // Nombre de la tabla configurada para registros de configuración (OPERLOG)
    protected $table = 'biometrico_configuraciones';

    // Campos habilitados para el registro de cambios en el dispositivo
    protected $fillable = [
        'sn',
        'parametro',
        'valor_anterior',
        'user_id',
        'fecha_hora',
        'detalles_raw',
    ];

    // Conversión de tipos para el manejo de fechas
    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    /**
     * Relación con el dispositivo.
     * Cada registro de configuración está vinculado a un equipo por su SN.
     */
    public function dispositivo(): BelongsTo
    {
        return $this->belongsTo(BiometricoDispositivo::class, 'sn', 'sn');
    }
}