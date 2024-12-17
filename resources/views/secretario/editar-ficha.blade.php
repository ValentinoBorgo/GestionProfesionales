<!DOCTYPE html>
<html>
<head>
    <title>Editar Ficha Médica</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Editar Ficha Médica</h1>
    
    <div id="error-container" style="color: red;"></div>

    <form id="fichaForm" action="{{ route('secretario.actualizar-ficha', $ficha->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nombre: 
            <input type="text" name="nombre" id="nombre" value="{{ $ficha->nombre }}" required>
            <span class="error" id="nombre-error"></span>
        </label><br>
        <label>Apellido: 
            <input type="text" name="apellido" id="apellido" value="{{ $ficha->apellido }}" required>
            <span class="error" id="apellido-error"></span>
        </label><br>
        <label>Email: 
            <input type="email" name="email" id="email" value="{{ $ficha->email }}" required>
            <span class="error" id="email-error"></span>
        </label><br>
        <label>Edad: 
            <input type="text" name="edad" id="edad" value="{{ $ficha->edad }}" required>
            <span class="error" id="edad-error"></span>
        </label><br>
        <label>Fecha Nacimiento: 
            <input type="text" name="fecha_nac" id="fecha_nac" value="{{ $ficha->fecha_nac }}" required>
            <span class="error" id="fecha_nac-error"></span>
        </label><br>
        <label>Ocupacion: 
            <input type="text" name="ocupacion" id="ocupacion" value="{{ $ficha->ocupacion }}" required>
            <span class="error" id="ocupacion-error"></span>
        </label><br>
        <label>Domicilio: 
            <input type="text" name="domicilio" id="domicilio" value="{{ $ficha->domicilio }}" required>
            <span class="error" id="domicilio-error"></span>
        </label><br>
        <label>Telefono: 
            <input type="text" name="telefono" id="telefono" value="{{ $ficha->telefono }}" required>
            <span class="error" id="telefono-error"></span>
        </label><br>
        <label>DNI: 
            <input type="text" name="dni" id="dni" value="{{ $ficha->dni }}" required>
            <span class="error" id="dni-error"></span>
        </label><br>
        <label>Localidad: 
            <input type="text" name="localidad" id="localidad" value="{{ $ficha->localidad }}" required>
            <span class="error" id="localidad-error"></span>
        </label><br>
        <label>Provincia: 
            <input type="text" name="provincia" id="provincia" value="{{ $ficha->provincia }}" required>
            <span class="error" id="provincia-error"></span>
        </label><br>
        <label>Persona Responsable: 
            <input type="text" name="persona_responsable" id="persona_responsable" value="{{ $ficha->persona_responsable }}" required>
            <span class="error" id="persona_responsable-error"></span>
        </label><br>
        <label>Vinculo: 
            <input type="text" name="vinculo" id="vinculo" value="{{ $ficha->vinculo }}" required>
            <span class="error" id="vinculo-error"></span>
        </label><br>
        <label>Tel. Responsable: 
            <input type="text" name="telefono_persona_responsable" id="telefono_persona_responsable" value="{{ $ficha->telefono_persona_responsable }}" required>
            <span class="error" id="telefono_persona_responsable-error"></span>
        </label><br>

        <button type="submit">Actualizar Ficha Médica</button>
    </form>
    <a href="{{ route('secretario.ver-pacientes') }}">Volver</a>

    <script>
    $(document).ready(function() {
        
        function isValidDateFormat(dateString) {
           
            const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
            
            if (!dateRegex.test(dateString)) {
                return false;
            }
            
          
            const date = new Date(dateString);
            return date instanceof Date && !isNaN(date);
        }

       
        function validateField(fieldId, errorMessage) {
            const field = $(`#${fieldId}`);
            const errorSpan = $(`#${fieldId}-error`);
            
            field.on('blur', function() {
                const value = $(this).val().trim();
                
                if (value === '') {
                    errorSpan.text(errorMessage);
                    field.addClass('error-input');
                } else if (fieldId === 'fecha_nac' && !isValidDateFormat(value)) {
                    errorSpan.text('Formato de fecha inválido. Use AAAA-MM-DD');
                    field.addClass('error-input');
                } else {
                    errorSpan.text('');
                    field.removeClass('error-input');
                }
            });
        }

        // Validaciones para cada campo
        validateField('nombre', 'El nombre es obligatorio');
        validateField('apellido', 'El apellido es obligatorio');
        validateField('email', 'El formato de email es incorrecto');
        validateField('dni', 'El DNI es obligatorio');
        validateField('edad', 'La edad es obligatoria');
        validateField('fecha_nac', 'La fecha de nacimiento es obligatoria y debe ser formato AAAA-MM-DD');
        validateField('ocupacion', 'La ocupación es obligatoria');
        validateField('domicilio', 'El domicilio es obligatorio');
        validateField('telefono', 'El teléfono es obligatorio');
        validateField('localidad', 'La localidad es obligatoria');
        validateField('provincia', 'La provincia es obligatoria');
        validateField('persona_responsable', 'El nombre del responsable es obligatorio');
        validateField('vinculo', 'El vínculo es obligatorio');
        validateField('telefono_persona_responsable', 'El teléfono del responsable es obligatorio');

        // Prevenir envío del formulario si hay errores
        $('#fichaForm').on('submit', function(e) {
            let hasErrors = false;
            
            
            $('input[required]').each(function() {
                const value = $(this).val().trim();
                if (value === '') {
                    $(`#${this.id}-error`).text(`El campo ${this.name} es obligatorio`);
                    $(this).addClass('error-input');
                    hasErrors = true;
                } else if (this.id === 'fecha_nac' && !isValidDateFormat(value)) {
                    $(`#${this.id}-error`).text('Formato de fecha inválido. Use AAAA-MM-DD');
                    $(this).addClass('error-input');
                    hasErrors = true;
                }
            });

            if (hasErrors) {
                e.preventDefault();
            }
        });
    });
    </script>

    <style>
        .error-input {
            border: 2px solid red;
        }
        .error {
            color: red;
            margin-left: 10px;
        }
    </style>
</body>
</html>