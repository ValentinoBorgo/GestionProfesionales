<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisponibilidadTable extends Migration
{
    public function up(): void
    {
        Schema::create('disponibilidad', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_sucursal');
            $table->unsignedBigInteger('id_sala');
            $table->unsignedBigInteger('id_profesional');
            $table->enum('dia', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo']);
            $table->time('horario_inicio');
            $table->time('horario_fin');

            // Definir claves foráneas
            $table->foreign('id_sucursal')
                  ->references('id')
                  ->on('sucursal')
                  ->onDelete('cascade') 
                  ->onUpdate('cascade'); 

            $table->foreign('id_sala')
                  ->references('id')
                  ->on('salas')
                  ->onDelete('cascade') 
                  ->onUpdate('cascade'); 

            $table->foreign('id_profesional')
                  ->references('id')
                  ->on('profesional')
                  ->onDelete('cascade') 
                  ->onUpdate('cascade'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disponibilidad');
    }
}