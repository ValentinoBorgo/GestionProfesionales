    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\SecretarioController;
    use App\Http\Controllers\PacienteController;
    use App\Http\Controllers\FichaMedicaController;
    use App\Http\Controllers\ProfesionalController;
    use App\Http\Controllers\TurnoController;
    use App\Http\Controllers\MailController;
    use App\Http\Controllers\AgendaController;
    use App\Filament\Pages\DetalleFichaMedica;
    use App\Filament\Pages\Pacientes;
    use App\Filament\Pages\EditarFichaMedica;
    use App\Filament\Pages\VerTurnos;
    use App\Filament\Pages\ModificarTurno;

    Route::get('/', function () {
        return redirect('dashboard/login');
    });

    // secretario
    Route::prefix('secretario')->middleware('role:2')->group(function () {

        //turnos
        Route::get('/', [SecretarioController::class, 'index']);
        Route::get('turnos', [TurnoController::class, 'turnos'])->name('secretario.turnos');
        Route::post('turnos', [TurnoController::class, 'storeTurno'])->name('secretario.turnos.store');
        
        Route::get('modificar-turno/{id}', [TurnoController::class, 'editarTurno'])->name('secretario.modificar-turno');
        Route::put('modificar-turno/{id}', [TurnoController::class, 'actualizarTurno'])->name('secretario.actualizar-turno');
        //ficha medica 
        Route::get('ver-pacientes', [FichaMedicaController::class, 'verPacientes'])->name('secretario.ver-pacientes');
        Route::put('actualizar-ficha/{id}', [FichaMedicaController::class, 'actualizarFicha'])->name('secretario.actualizar-ficha');
    });
    Route::get('/ver-turnos', VerTurnos::class)->name('filament.ver-turnos');
    Route::get('/editar-ficha/{id}', EditarFichaMedica::class)->name('filament.editar-ficha');
    Route::get('/modificar-turnos/{id}', ModificarTurno::class)->name('filament.modificar-turno');
    // mail/recordatorio
    Route::get('/enviar-correo', [MailController::class, 'enviarCorreo']);


    //profesional
    Route::prefix('profesional')->middleware('role:3')->group(function () {
        Route::get('/', [TurnoController::class, 'verTurnosProfesional'])->name('profesional.turnos');
        Route::get('/mis-pacientes', [PacienteController::class, 'verTurnosProfesional'])->name('profesional.turnos');
        Route::get('/ver-mis-turnos', [TurnoController::class, 'verTurnosProfesional'])->name('profesional.turnos');
        Route::get('/modificar-turno', [TurnoController::class, 'verTurnosProfesional'])->name('profesional.turnos');
        Route::get('/editar-ficha-mi-paciente/{id}', [FichaMedicaController::class, 'verTurnosProfesional'])->name('profesional.turnos');
    });
    // rutas de busqueda
    Route::get('/secretarios/search', [SecretarioController::class, 'search'])->name('secretarios.search');
    Route::get('/profesionales/search', [ProfesionalController::class, 'search'])->name('profesionales.search');
    Route::get('/pacientes/search', [PacienteController::class, 'search'])->name('pacientes.search');

    Route::get('/detalle-ficha/{id}', DetalleFichaMedica::class)->name('fichaMedica.show');
    Route::get('/pacientes', Pacientes::class)->name('pacientes.index');
    Route::get('/turno/cancelar/{id}', [TurnoController::class, 'cancelarTurno'])->name('turno.cancelarTurno');
