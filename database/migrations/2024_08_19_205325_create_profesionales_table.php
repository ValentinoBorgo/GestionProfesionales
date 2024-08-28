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
        Schema::create('profesionales', function (Blueprint $table) {
            $table->id(); // Esto crea la columna 'id' como clave primaria autoincremental
            $table->string('titulo')->nullable();
            $table->timestamps();

            // Si 'id' se refiere a una clave foránea, define la relación aquí
            $table->foreignId('id_persona')->constrained('users')->onDelete('cascade'); // Ejemplo si corresponde
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profesionales');
    }
};
