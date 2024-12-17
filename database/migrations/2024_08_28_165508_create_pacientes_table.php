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
        Schema::create('paciente', function (Blueprint $table) {
            $table->id(); // Esto crea la columna 'id' como clave primaria autoincremental
            $table->string('fecha_alta')->nullable();
            $table->timestamps();

            $table->foreignId('id_ficha_medica')->nullable()->constrained('ficha_medica')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paciente');
    }
};
