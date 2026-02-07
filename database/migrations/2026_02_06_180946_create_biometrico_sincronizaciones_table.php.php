<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biometrico_sincronizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plantilla_id')->constrained('biometrico_plantillas')->onDelete('cascade');
            $table->string('dispositivo_sn');
            $table->string('estado')->default('pendiente'); // pendiente, sincronizado, error
            $table->timestamp('fecha_sincronizacion')->nullable();
            $table->timestamps();

            // Relacion con la tabla de dispositivos por el Numero de Serie
            $table->foreign('dispositivo_sn')->references('sn')->on('biometrico_dispositivos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biometrico_sincronizaciones');
    }
};