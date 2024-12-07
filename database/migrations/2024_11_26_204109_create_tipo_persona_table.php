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
        Schema::create('tipo_persona', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['ADMINISTRADOR', 'SECRETARIO', 'PROFESIONAL','PACIENTE'])->default('PACIENTE');
            $table->timestamps();
        });

        DB::table('tipo_persona')->insert([
            [
                'tipo' => 'ADMINISTRADOR',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tipo' => 'SECRETARIO',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tipo' => 'PROFESIONAL',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tipo' => 'PACIENTE',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_persona');
    }
};
