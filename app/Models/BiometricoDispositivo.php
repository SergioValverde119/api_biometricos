<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BiometricoDispositivo extends Model
{
    use HasFactory;

    /**
     * En el nombre de Jehová Dios y Jesús, definimos el modelo para los equipos físicos.
     */

    // Especificamos el nombre de la tabla configurada en el Canvas
    protected $table = 'biometrico_dispositivos';

    // Campos que permitimos llenar de forma masiva
    protected $fillable = [
        'nombre',
        'sn',
        'ip',
        'modelo',
        'ultima_conexion',
        'activo',
    ];

    // Conversión automática de tipos de datos
    protected $casts = [
        'ultima_conexion' => 'datetime',
        'activo' => 'boolean',
    ];

    /**
     * Relación con las asistencias.
     * Un dispositivo tiene muchas asistencias a través de su Número de Serie (SN).
     */
    public function asistencias(): HasMany
    {
        return $this->hasMany(BiometricoAsistencia::class, 'sn', 'sn');
    }

    /**
     * Relación con las configuraciones técnicas (OPERLOG).
     */
    public function configuraciones(): HasMany
    {
        return $this->hasMany(BiometricoConfiguracion::class, 'sn', 'sn');
    }

    /**
     * Relación con la cola de comandos pendientes (getrequest).
     */
    public function comandos(): HasMany
    {
        return $this->hasMany(BiometricoComando::class, 'sn', 'sn');
    }
}