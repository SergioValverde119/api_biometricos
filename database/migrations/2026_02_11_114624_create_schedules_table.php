<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del horario (ej. "Matutino 9-6")
            $table->time('entry_time')->nullable(); // Hora de entrada
            $table->time('exit_time')->nullable();  // Hora de salida
            $table->integer('tolerance_minutes')->default(0); // Minutos de tolerancia
            
            // ID de BioTime para referencias futuras
            $table->unsignedBigInteger('biotime_id')->nullable()->index();
            
            $table->timestamps();
        });

        // Opcional: Agregar la llave foránea a la tabla de empleados si ya existe
        /*
        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('schedule_id')->references('id')->on('schedules')->nullOnDelete();
        });
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};