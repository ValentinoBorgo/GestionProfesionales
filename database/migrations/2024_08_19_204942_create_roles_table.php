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
        Schema::create('rol', function (Blueprint $table) {
            $table->id(); // Define el campo id_rol como clave primaria
            $table->string('nombre'); // Define el campo nombre como una cadena de texto
            $table->timestamps(); // Agrega los campos created_at y updated_at
        });
        
        DB::table('rol')->insert([
            [
                'nombre' => 'ROLE_ADMIN',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'ROLE_SECRETARIO',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'ROLE_PROFESIONAL',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rol');
    }
};

