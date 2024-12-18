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

                $sucursales = DB::table('sucursal')->get();

                $salas = [];
                foreach ($sucursales as $sucursal) {
                    $salas[] = [
                        'nombre' => 'Sala de Consulta ' . $sucursal->id,
                        'tipo' => 'Consulta',
                        'id_sucursal' => $sucursal->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $salas[] = [
                        'nombre' => 'Sala de Cirugía ' . $sucursal->id,
                        'tipo' => 'Cirugía',
                        'id_sucursal' => $sucursal->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $salas[] = [
                        'nombre' => 'Sala de Rehabilitación ' . $sucursal->id,
                        'tipo' => 'Rehabilitación',
                        'id_sucursal' => $sucursal->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
        
                DB::table('salas')->insert($salas);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salas');
    }
};
