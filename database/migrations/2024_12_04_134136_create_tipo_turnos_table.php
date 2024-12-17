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
        Schema::create('tipo_turnos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('nombre');
            $table->timestamps();
        });

        DB::table('tipo_turnos')->insert([
            [
                'codigo' => 'Consulta',
                'nombre' => 'Consulta',
                'created_at' => '2024-12-16 22:23:18',
                'updated_at' => '2024-12-16 22:23:18',
            ],
            [
                'codigo' => 'Cirugía',
                'nombre' => 'Cirugía',
                'created_at' => '2024-12-16 22:23:18',
                'updated_at' => '2024-12-16 22:23:18',
            ],
            [
                'codigo' => 'Rehabilitación',
                'nombre' => 'Rehabilitación',
                'created_at' => '2024-12-16 22:23:18',
                'updated_at' => '2024-12-16 22:23:18',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_turnos');
    }
};
