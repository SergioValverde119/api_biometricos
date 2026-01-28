<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * En el nombre de Jehová Dios y Jesús, definimos la estructura profesional para los 6 dispositivos.
     */
    public function up(): void
    {
        // 1. DISPOSITIVOS (Los 6 equipos físicos SpeedFace-V5L)
        Schema::create('biometrico_dispositivos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');          // Ej: Entrada Principal, Recepción
            $table->string('sn')->unique();    // Número de serie único (CQUG...)
            $table->string('ip')->nullable();
            $table->string('modelo')->default('SpeedFace-V5L');
            $table->dateTime('ultima_conexion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // 2. ASISTENCIAS (Registros de entrada y salida - ATTLOG)
        Schema::create('biometrico_asistencias', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->index();      // ID del empleado en el dispositivo
            $table->dateTime('fecha_hora')->index(); 
            $table->integer('estado')->default(0);   // 0: Entrada, 1: Salida
            $table->integer('tipo_verificacion')->default(0); // 1: Huella, 15: Rostro
            $table->string('sn')->index();           // SN del dispositivo que lo envía
            $table->timestamps();

            // Relación con la tabla de dispositivos para integridad de datos
            $table->foreign('sn')->references('sn')->on('biometrico_dispositivos')->onDelete('cascade');
        });

        // 3. CONFIGURACIONES (Log de ajustes del equipo - OPERLOG)
        // Aquí guardamos cuando cambian IP, DHCP, entran al menú, etc.
        Schema::create('biometrico_configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('sn')->index();
            $table->string('parametro');        // Ej: DHCP, IPAddress, AdminLogin
            $table->string('valor_anterior')->nullable();
            $table->string('user_id')->nullable(); // Quién hizo el cambio si aplica
            $table->dateTime('fecha_hora');
            $table->text('detalles_raw')->nullable(); // La trama completa de Fiddler por si acaso
            $table->timestamps();

            $table->foreign('sn')->references('sn')->on('biometrico_dispositivos')->onDelete('cascade');
        });

        // 4. COMANDOS (Cola de tareas para enviar al equipo vía getrequest)
        Schema::create('biometrico_comandos', function (Blueprint $table) {
            $table->id();
            $table->string('sn')->index();
            $table->string('comando');          // La instrucción ADMS (ej: SET OPTION DateTime=...)
            $table->enum('estado', ['pendiente', 'enviado', 'completado', 'error'])->default('pendiente');
            $table->text('respuesta_dispositivo')->nullable();
            $table->timestamp('fecha_ejecucion')->nullable();
            $table->timestamps();

            $table->foreign('sn')->references('sn')->on('biometrico_dispositivos')->onDelete('cascade');
        });

        // 5. PLANTILLAS (Datos biométricos: Rostros y Huellas)
        Schema::create('biometrico_plantillas', function (Blueprint $table) {
            $table->id();
            $table->string('user_id_biometrico')->index();
            $table->enum('tipo', ['huella', 'rostro', 'palma']);
            $table->integer('indice')->default(0); 
            $table->longText('template');          // El mapa binario largo del rostro o huella
            $table->string('version_algoritmo');   // Necesario para mover plantillas entre equipos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biometrico_plantillas');
        Schema::dropIfExists('biometrico_comandos');
        Schema::dropIfExists('biometrico_configuraciones');
        Schema::dropIfExists('biometrico_asistencias');
        Schema::dropIfExists('biometrico_dispositivos');
    }
};