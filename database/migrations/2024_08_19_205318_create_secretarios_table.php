<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secretario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('users');
            
            $table->date('fecha_baja')->nullable(); 
            
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secretario');
    }
};
