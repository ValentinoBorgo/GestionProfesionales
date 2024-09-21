<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SecretarioController;
use App\Http\Controllers\PacienteController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('secretario', [SecretarioController::class, 'index']);
Route::get('secretario/ver-pacientes', [PacienteController::class, 'verPacientes']);
Route::get('secretario/modificar-turnos', [SecretarioController::class, 'modificarTurnos']);
Route::get('secretario/agendar-turnos', [SecretarioController::class, 'agendarTurnos']);
Route::get('secretario/dar-alta-paciente', [PacienteController::class, 'create'])->name('pacientes.create');
Route::post('secretario/dar-alta-paciente', [PacienteController::class, 'store'])->name('pacientes.store');