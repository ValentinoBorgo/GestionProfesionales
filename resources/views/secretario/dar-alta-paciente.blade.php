<form action="{{ route('pacientes.store') }}" method="POST">
    @csrf

    <!-- Campos para el Usuario -->
    <h3>Datos del Paciente</h3>
    <div>
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" required>
    </div>

    <div>
        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required>
    </div>

    <div>
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required>
    </div>

    <div>
        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" required>
    </div>

    <div>
        <label for="domicilio">Domicilio:</label>
        <input type="text" id="domicilio" name="domicilio" required>
    </div>

    <div>
        <label for="nombre_usuario">Nombre de Usuario:</label>
        <input type="text" id="nombre_usuario" name="nobre_usuario" required>
    </div>

    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>

    <div>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
    </div>

    <!-- Campos para la Ficha Médica -->
    <h3>Datos de la Ficha Médica</h3>
    <div>
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
    </div>
    <div>
        <label for="fecha_nac">Fecha de Nacimiento:</label>
        <input type="text" id="fecha_nac" name="fecha_nac" required>
    </div>

    <div>
        <label for="ocupacion">Ocupación:</label>
        <input type="text" id="ocupacion" name="ocupacion" required>
    </div>

    <div>
        <label for="localidad">Localidad:</label>
        <input type="text" id="localidad" name="localidad" required>
    </div>

    <div>
        <label for="provincia">Provincia:</label>
        <input type="text" id="provincia" name="provincia" required>
    </div>

    <div>
        <label for="persona_responsable">Persona Responsable:</label>
        <input type="text" id="persona_responsable" name="persona_responsable" required>
    </div>

    <div>
        <label for="vinculo">Vínculo:</label>
        <input type="number" id="vinculo" name="vinculo" required>
    </div>

    <div>
        <label for="dni">DNI:</label>
        <input type="number" id="dni" name="dni" required>
    </div>

    <div>
        <label for="telefono_persona_responsable">Teléfono Persona Responsable:</label>
        <input type="text" id="telefono_persona_responsable" name="telefono_persona_responsable" required>
    </div>

    <!-- Botón para enviar el formulario -->
    <button type="submit">Dar de alta paciente</button>
</form>

