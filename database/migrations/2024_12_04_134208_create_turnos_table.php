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
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('hora_fecha');
            
            $table->unsignedBigInteger('id_profesional');
            $table->foreign('id_profesional')->references('id')->on('profesional');
            
            $table->unsignedBigInteger('id_paciente');
            $table->foreign('id_paciente')->references('id')->on('paciente');
            
            $table->unsignedBigInteger('id_secretario');
            $table->foreign('id_secretario')->references('id')->on('secretario');
            
            $table->unsignedBigInteger('id_tipo_turno');
            $table->foreign('id_tipo_turno')->references('id')->on('tipo_turnos');
            
            $table->unsignedBigInteger('id_estado');
            $table->foreign('id_estado')->references('id')->on('estado_turnos');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};
