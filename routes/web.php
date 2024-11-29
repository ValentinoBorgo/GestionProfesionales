<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SecretarioController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\FichaMedicaController;


Route::get('/', function () {
    return redirect('dashboard/login');
});


Route::prefix('secretario')->group(function () {
    Route::get('/', [SecretarioController::class, 'index']);
    Route::get('modificar-turnos', [SecretarioController::class, 'modificarTurnos']);
    Route::get('agendar-turnos', [SecretarioController::class, 'agendarTurnos']);


    Route::get('dar-alta-paciente', [PacienteController::class, 'create'])->name('pacientes.create');
    Route::post('dar-alta-paciente', [PacienteController::class, 'store'])->name('pacientes.store');

    // Ficha Medica routes
    Route::get('ver-pacientes', [FichaMedicaController::class, 'verPacientes'])->name('secretario.ver-pacientes');
    Route::get('editar-ficha/{id}', [FichaMedicaController::class, 'editarFicha'])->name('secretario.editar-ficha');
    Route::put('actualizar-ficha/{id}', [FichaMedicaController::class, 'actualizarFicha'])->name('secretario.actualizar-ficha');
});