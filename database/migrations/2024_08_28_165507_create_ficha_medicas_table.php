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
        Schema::create('ficha_medica', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->string('email')->unique();
            $table->string('edad')->nullable();
            $table->string('fecha_nac')->nullable();
            $table->string('ocupacion')->nullable();
            $table->string('domicilio')->nullable();
            $table->integer('telefono')->nullable();
            $table->string('localidad')->nullable();
            $table->string('provincia')->nullable();
            $table->string('persona_responsable')->nullable();
            $table->integer('vinculo')->nullable();
            $table->string('dni')->nullable();
            $table->string( 'telefono_persona_responsable')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    Schema::dropIfExists('ficha_medica');
    }
};
