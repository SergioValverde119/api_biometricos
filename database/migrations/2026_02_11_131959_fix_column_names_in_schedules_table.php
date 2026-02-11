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
        Schema::table('schedules', function (Blueprint $table) {
            // Lo que antes llamamos 'tolerance_minutes' en realidad son los 'minutos_laborales'
            $table->renameColumn('tolerance_minutes', 'work_minutes');

            // Lo que antes llamamos 'biotime_id' en realidad es la 'tolerancia_real_minutos'
            // Nota: Al renombrarla, conservará el tipo (unsignedBigInteger), lo cual está bien para números positivos.
            $table->renameColumn('biotime_id', 'tolerance_minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->renameColumn('work_minutes', 'tolerance_minutes');
            $table->renameColumn('tolerance_minutes', 'biotime_id');
        });
    }
};