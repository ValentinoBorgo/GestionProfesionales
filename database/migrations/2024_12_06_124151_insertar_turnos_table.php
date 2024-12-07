<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;  // Añade esta línea para usar DB::table()

return new class extends Migration
{
    public function up(): void
{
    DB::table('estado_turnos')->insert([
        [
            'codigo' => 'ASIGNADO',
            'nombre' => 'Asignado',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'codigo' => 'REPROGRAMADO',
            'nombre' => 'Reprogramado',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'codigo' => 'CANCELADO_CLIENTE',
            'nombre' => 'Cancelado por el cliente',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'codigo' => 'CANCELADO_PROFESIONAL',
            'nombre' => 'Cancelado por el profesional',
            'created_at' => now(),
            'updated_at' => now()
        ]
    ]);
}

public function down(): void
{
    DB::table('estado_turnos')->whereIn('codigo', [
        'ASIGNADO', 
        'REPROGRAMADO', 
        'CANCELADO_CLIENTE', 
        'CANCELADO_PROFESIONAL'
    ])->delete();
}
};
