<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('rol_permiso')) {
            Schema::create('rol_permiso', function (Blueprint $table) {
                $table->unsignedBigInteger('id_rol');
                $table->unsignedBigInteger('id_permiso');
                $table->timestamps();
        
                // Definición de claves foráneas
                $table->foreign('id_rol')->references('id')->on('rol')->onDelete('cascade');
                $table->foreign('id_permiso')->references('id')->on('permiso')->onDelete('cascade');
        
                // Clave primaria compuesta
                $table->primary(['id_rol', 'id_permiso']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('rol_permiso');
    }
};


