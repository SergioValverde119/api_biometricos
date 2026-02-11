<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     * En el nombre de Dios, permitimos estos campos para la importación.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id', // Número de empleado / Nómina (ej. "1", "3")
        'name',        // Nombre de pila (ej. "Elvis")
        'last_name',   // Apellidos (ej. "Brito Villa")
        'schedule_id', // ID del Horario asignado (FK)
        'biotime_id',  // ID interno de referencia con BioTime (para sincronización)
    ];

    /**
     * Relación con el Horario.
     * Un empleado pertenece a un horario.
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Obtener el nombre completo del empleado.
     * Útil para mostrar en listados o reportes.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->name} {$this->last_name}");
    }
}