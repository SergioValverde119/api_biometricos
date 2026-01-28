<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricoPlantilla extends Model
{
    use HasFactory;

    /**
     * En el nombre de Jehová Dios y Jesús, definimos el modelo para las huellas y rostros.
     */

    // Nombre de la tabla para el almacenamiento de plantillas biométricas
    protected $table = 'biometrico_plantillas';

    // Campos para el guardado de la información biométrica sensible
    protected $fillable = [
        'user_id_biometrico',
        'tipo',
        'indice',
        'template',
        'version_algoritmo',
    ];

    /**
     * Nota técnica: No incluimos relación con dispositivos aquí porque las plantillas
     * pertenecen al usuario y pueden ser enviadas a CUALQUIER dispositivo de los 6.
     */
}