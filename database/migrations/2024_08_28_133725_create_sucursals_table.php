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
        Schema::create('sucursal', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre')->nullable();
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('provincia')->nullable();
            $table->string('razon_social')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->bigInteger('telefono')->nullable();
            $table->string('horarios')->nullable();
        });

        DB::table('sucursal')->insert([
            [
                'nombre' => 'Sucursal Centro',
                'direccion' => 'Av. Principal 123',
                'ciudad' => 'Ciudad Central',
                'provincia' => 'Provincia Central',
                'razon_social' => 'Sucursal Centro S.A.',
                'codigo_postal' => '1000',
                'telefono' => 123456789,
                'horarios' => 'Lunes a Viernes 9 a 18hs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Sucursal Norte',
                'direccion' => 'Calle Norte 456',
                'ciudad' => 'Ciudad Norteña',
                'provincia' => 'Provincia Norte',
                'razon_social' => 'Sucursal Norte S.A.',
                'codigo_postal' => '2000',
                'telefono' => 987654321,
                'horarios' => 'Lunes a Sábado 8 a 16hs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Sucursal Sur',
                'direccion' => 'Camino Sur 789',
                'ciudad' => 'Ciudad Sureña',
                'provincia' => 'Provincia Sur',
                'razon_social' => 'Sucursal Sur S.A.',
                'codigo_postal' => '3000',
                'telefono' => 123987456,
                'horarios' => 'Martes a Domingo 10 a 19hs',
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
        Schema::dropIfExists('sucursal');
    }
};
