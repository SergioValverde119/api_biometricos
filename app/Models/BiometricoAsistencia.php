<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiometricoAsistencia extends Model
{
    use HasFactory;

    /**
     * En el nombre de Jehová Dios y Jesús, definimos el modelo para las checadas de asistencia.
     */

    // Nombre de la tabla configurada en la migración profesional
    protected $table = 'biometrico_asistencias';

    // Campos habilitados para llenado masivo desde el controlador
    protected $fillable = [
        'user_id',
        'fecha_hora',
        'estado',
        'tipo_verificacion',
        'sn',
    ];

    // Conversión de tipos para facilitar el manejo de fechas
    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    /**
     * Relación con el dispositivo.
     * Cada asistencia pertenece a un dispositivo específico identificado por su SN.
     */
    public function dispositivo(): BelongsTo
    {
        return $this->belongsTo(BiometricoDispositivo::class, 'sn', 'sn');
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'user_id', 'employee_id');
    }
}