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
        // Schema::create('rol_permiso', function (Blueprint $table) {
        //     $table->unsignedBigInteger('id_rol');
        //     $table->unsignedBigInteger('id_permiso');
        //     $table->timestamps();

        //     // Definición de claves foráneas
        //     $table->foreign('id_rol')->references('id')->on('rol')->onDelete('cascade');
        //     $table->foreign('id_permiso')->references('id')->on('permiso')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rol_permiso');
    }
};

