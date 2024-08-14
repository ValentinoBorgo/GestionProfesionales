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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('apellido')->nullable();
            $table->string('telefono')->nullable();
            $table->integer('edad')->nullable();
            $table->datetime('fecha_nac')->nullable();
            $table->string('domicilio')->nullable();
            $table->integer('id_rol')->nullable();
            $table->integer('id_tipo')->nullable();
            $table->integer('id_sucursal')->nullable();
            $table->string('nobre_usuario')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
