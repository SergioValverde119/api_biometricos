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
        Schema::table('employees', function (Blueprint $table) {
            // Agregamos apellido después del nombre
            $table->string('last_name')->nullable()->after('name');
            
            // Agregamos la columna para el ID del Horario (índice 3 del CSV)
            $table->unsignedBigInteger('schedule_id')->nullable()->index()->after('last_name');

            // Agregamos la columna para el ID interno de BioTime (última del CSV)
            $table->unsignedBigInteger('biotime_id')->nullable()->index()->after('schedule_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['last_name', 'schedule_id', 'biotime_id']);
        });
    }
};