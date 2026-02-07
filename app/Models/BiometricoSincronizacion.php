<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiometricoSincronizacion extends Model
{
    protected $table = 'biometrico_sincronizaciones';
    
    protected $fillable = [
        'plantilla_id',
        'dispositivo_sn',
        'estado',
        'fecha_sincronizacion'
    ];
}