    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\SecretarioController;
    use App\Http\Controllers\PacienteController;
    use App\Http\Controllers\FichaMedicaController;
    use App\Http\Controllers\ProfesionalController;
    use App\Http\Controllers\TurnoController;
    use App\Http\Controllers\MailController;

    Route::get('/', function () {
        return redirect('dashboard/login');
    });

    // secretario
    Route::prefix('secretario')->middleware('role:2')->group(function () {

        //turnos
        Route::get('/', [SecretarioController::class, 'index']);
        Route::get('turnos', [TurnoController::class, 'turnos'])->name('secretario.turnos');
        Route::post('turnos', [TurnoController::class, 'storeTurno'])->name('secretario.turnos.store');
        Route::get('ver-turnos', [TurnoController::class, 'verTurnos'])->name('secretario.ver-turnos');
        
        Route::get('dar-alta-paciente', [PacienteController::class, 'create'])->name('pacientes.create');
        Route::post('dar-alta-paciente', [PacienteController::class, 'store'])->name('pacientes.store');
        Route::get('modificar-turno/{id}', [TurnoController::class, 'editarTurno'])->name('secretario.modificar-turno');
        Route::put('modificar-turno/{id}', [TurnoController::class, 'actualizarTurno'])->name('secretario.actualizar-turno');
        //ficha medica 
        Route::get('ver-pacientes', [FichaMedicaController::class, 'verPacientes'])->name('secretario.ver-pacientes');
        Route::get('editar-ficha/{id}', [FichaMedicaController::class, 'editarFicha'])->name('secretario.editar-ficha');
        Route::put('actualizar-ficha/{id}', [FichaMedicaController::class, 'actualizarFicha'])->name('secretario.actualizar-ficha');
    });
    Route::get('/enviar-correo', [MailController::class, 'enviarCorreo']);
    // rutas de busqueda
    Route::get('/secretarios/search', [SecretarioController::class, 'search'])->name('secretarios.search');
    Route::get('/profesionales/search', [ProfesionalController::class, 'search'])->name('profesionales.search');
    Route::get('/pacientes/search', [PacienteController::class, 'search'])->name('pacientes.search');


// Route::prefix('profesional')->group(function () {
//     Route::get('/', [ProfesionalController::class, 'index']);
// });

