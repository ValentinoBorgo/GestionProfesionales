<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->unsignedBigInteger('id_sucursal');
            $table->string('nombre');
            $table->timestamps();

            $table->foreign('id_sucursal')->references('id')->on('sucursal')->onDelete('cascade');
        });

           // Insertar datos de prueba
    DB::table('salas')->insert([
        [
            'nombre' => 'Sala de Consulta 1',
            'tipo' => 'Consulta',
            'id_sucursal' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nombre' => 'Sala de Cirugía 1',
            'tipo' => 'Cirugía',
            'id_sucursal' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nombre' => 'Sala de Rehabilitación 1',
            'tipo' => 'Rehabilitación',
            'id_sucursal' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salas');
    }
};
