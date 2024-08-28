<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('sucursal_usuario', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
        $table->foreignId('id_sucursal')->constrained('sucursales')->onDelete('cascade');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursal_user');
    }
};
