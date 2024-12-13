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
    Schema::table('turnos', function (Blueprint $table) {
        $table->unsignedBigInteger('id_sala')->nullable(); 
        $table->foreign('id_sala')->references('id')->on('salas')->onDelete('cascade');
    });
}

    public function down()
{
    Schema::table('turnos', function (Blueprint $table) {
        $table->dropForeign(['id_sala']);
        $table->dropColumn('id_sala');
    });
}
};
