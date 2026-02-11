<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'day_of_week',
        'entry_time',
        'exit_time',
        'work_minutes',       // Antes tolerance_minutes
        'tolerance_minutes',  // Antes biotime_id
        // Quitamos biotime_id del fillable porque ya no existe como tal en esta tabla por ahora
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}