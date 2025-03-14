@php
// Si existe 'id_profesional' en la query, se utiliza; sino se usa el del turno actual
$selectedProfessional = request()->has('id_profesional') ? request()->get('id_profesional') : $turno->id_profesional;
  // Usamos el turno actual para el profesional
  $appointments = \App\Models\Turno::where('id_estado', 1)
        ->where('id_profesional', $selectedProfessional)
        ->get(['hora_fecha'])
        ->each(function($appointment) {
            $appointment->hora_fecha = $appointment->hora_fecha->format('Y-m-d\TH:i:s');
        });
    $appointmentsJson = $appointments->toJson();

    // Obtén el profesional usando el id seleccionado
    $profesional = \App\Models\Profesional::find($selectedProfessional);

    // Disponibilidades del profesional
    $disponibilidades = $profesional ? \App\Models\Disponibilidad::where('id_usuario', $profesional->id_persona)->get() : collect();
    $disponibilidadesJson = $disponibilidades->toJson();

    // Ausencias del profesional
    $ausencias = $profesional ? \App\Models\Ausencias::where('id_usuario', $profesional->id_persona)->get() : collect();
    $ausenciasJson = $ausencias->toJson();
@endphp
<x-filament::page>
  <form wire:submit.prevent="submit">
    {{ $this->form }}

    <!-- Calendario Semanal -->
    <div wire:ignore>
      <div class="calendar-container">
        <!-- Encabezado del calendario: botones y rango de semana -->
        <div class="calendar-header">
          <button id="prevWeek">&lt;</button>
          <div id="weekRange"></div>
          <button id="nextWeek">&gt;</button>
        </div>

        <!-- Grid del calendario: encabezados de días y celdas -->
        <div class="calendar-grid" id="calendarGrid">
          <div class="calendar-day-header"></div>
          <div class="calendar-day-header">Lunes</div>
          <div class="calendar-day-header">Martes</div>
          <div class="calendar-day-header">Miércoles</div>
          <div class="calendar-day-header">Jueves</div>
          <div class="calendar-day-header">Viernes</div>
          <div class="calendar-day-header">Sábado</div>
          <div class="calendar-day-header">Domingo</div>
        </div>
      </div>
    </div>

    @if ($errors->any())
      <div style="color: #b91c1c; background-color: #fef2f2; border: 1px solid #fca5a5; border-radius: 5px; padding: 10px; margin-top: 10px;">
          <ul style="margin-left: 20px; list-style-type: disc;">
              @foreach ($errors->all() as $error)
                  <li style="font-weight: bold;">{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif

    <div class="mt-4">
        <x-filament::button type="submit">Actualizar Turno</x-filament::button>
    </div>
  </form>

  <a href="{{ route('filament.ver-turnos') }}" class="btn btn-secondary">Volver</a>

  <style>
  /* Estilo para las celdas con disponibilidad */
  .calendar-cell.available {
      background-color: #fafafa;
  }
  /* Estilo para las celdas sin disponibilidad (puedes ajustar el color) */
  .calendar-cell.not-available {
      background-color:rgb(223, 216, 216);
      opacity: 0.5;
  }
  /* El resto de tus estilos se mantienen */
  .calendar-container {
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 5px;
      overflow: hidden;
  }
  .calendar-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      background-color: #f0f0f0;
  }
  .calendar-header button {
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
  }
  .calendar-header #weekRange {
      font-size: 16px;
      font-weight: bold;
  }
  .calendar-grid {
      display: grid;
      grid-template-columns: 80px repeat(7, 1fr);
      border-top: 1px solid #ccc;
      border-left: 1px solid #ccc;
  }
  .calendar-day-header {
      background-color: #e0e0e0;
      border-right: 1px solid #ccc;
      border-bottom: 1px solid #ccc;
      text-align: center;
      padding: 5px;
      font-weight: bold;
  }
  .calendar-hour {
      border-right: 1px solid #ccc;
      border-bottom: 1px solid #ccc;
      text-align: center;
      line-height: 50px;
      height: 50px;
      background-color: #fafafa;
  }
  .calendar-cell {
      border-right: 1px solid #ccc;
      border-bottom: 1px solid #ccc;
      height: 50px;
      position: relative;
      text-align: center;
      line-height: 50px;
  }
  .calendar-cell.appointment {
      background-color: rgb(0, 128, 0);
      color: white;
      font-weight: bold;
  }
</style>


<script>

// Función para actualizar el query string (ya definida)
function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
        return uri + separator + key + "=" + value;
    }
}
function isSlotAvailable(dayIndex, hour, minute) {
    let dayName = diasSemana[dayIndex];
    let disponibilidadesDelDia = disponibilidades.filter(function(d) {
        return d.dia.toLowerCase() === dayName;
    });
    let slotTime = hour * 60 + minute;
    for (let i = 0; i < disponibilidadesDelDia.length; i++) {
        let d = disponibilidadesDelDia[i];
        let startParts = d.horario_inicio.split(':');
        let endParts = d.horario_fin.split(':');
        let startTime = parseInt(startParts[0]) * 60 + parseInt(startParts[1]);
        let endTime = parseInt(endParts[0]) * 60 + parseInt(endParts[1]);
        if (slotTime >= startTime && slotTime < endTime) {
            return true;
        }
    }
    return false;
}

// Parseamos los JSON enviados desde PHP
var appointments = {!! $appointmentsJson !!};
var disponibilidades = {!! $disponibilidadesJson !!};
var ausencias = {!! $ausenciasJson !!};

// Array para mapear el índice de día (0 = lunes, ...) con su nombre en minúscula
const diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];

// Función que calcula el rango global de horas basado en las disponibilidades
function calcularRangoHoras() {
    let minHour = 24;
    let maxHour = 0;
    if (disponibilidades.length === 0) {
        return { minHour: 0, maxHour: 24 };
    }
    disponibilidades.forEach(function(d) {
        let startHour = parseInt(d.horario_inicio.split(':')[0]);
        let endHour = parseInt(d.horario_fin.split(':')[0]);
        if (startHour < minHour) minHour = startHour;
        if (endHour > maxHour) maxHour = endHour;
    });
    return { minHour, maxHour };
}

// Función para determinar si la hora (según disponibilidad) está dentro de una ausencia
function isAbsent(cellDate) {
    for (let i = 0; i < ausencias.length; i++) {
        let absenceStart = new Date(ausencias[i].fecha_inicio);
        let absenceEnd   = new Date(ausencias[i].fecha_fin);
        if (cellDate >= absenceStart && cellDate < absenceEnd) {
            return true;
        }
    }
    return false;
}

// Función para determinar si para el día y hora indicados existe disponibilidad
function isHourAvailable(dayIndex, hour) {
    let dayName = diasSemana[dayIndex];
    let disponibilidadesDelDia = disponibilidades.filter(function(d) {
        return d.dia.toLowerCase() === dayName;
    });
    for (let i = 0; i < disponibilidadesDelDia.length; i++) {
        let d = disponibilidadesDelDia[i];
        let startHour = parseInt(d.horario_inicio.split(':')[0]);
        let endHour = parseInt(d.horario_fin.split(':')[0]);
        if (hour >= startHour && hour < endHour) {
            return true;
        }
    }
    return false;
}

// Función para obtener el lunes de la semana de una fecha dada
function getMonday(d) {
    d = new Date(d);
    let day = d.getDay(),
        diff = d.getDate() - day + (day === 0 ? -6 : 1);
    return new Date(d.setDate(diff));
}

// Función para generar las filas del calendario
function generateCalendarRows() {
    const grid = document.getElementById('calendarGrid');
    // Elimina filas existentes, dejando solo la fila de encabezados (8 elementos)
    while (grid.children.length > 8) {
        grid.removeChild(grid.lastElementChild);
    }
    
    const { minHour, maxHour } = calcularRangoHoras();
    const monday = getMonday(currentDate);
    
    // Genera filas para cada media hora dentro del rango
    for (let hour = minHour; hour < maxHour; hour++) {
        for (let minute = 0; minute < 60; minute += 30) {
            // Columna de la hora y minuto (ej. "09:00" o "09:30")
            let timeCell = document.createElement('div');
            timeCell.className = 'calendar-hour';
            timeCell.textContent = (hour < 10 ? '0' + hour : hour) + ":" + (minute === 0 ? "00" : "30");
            grid.appendChild(timeCell);
            
            // Celdas para cada día de la semana
            for (let day = 0; day < 7; day++) {
                let cell = document.createElement('div');
                cell.className = 'calendar-cell';
                cell.setAttribute('data-day', day);
                cell.setAttribute('data-hour', hour);
                cell.setAttribute('data-minute', minute);
                
                // Calcula la fecha y hora exacta de la celda
                let cellDate = new Date(monday);
                cellDate.setDate(cellDate.getDate() + day);
                cellDate.setHours(hour, minute, 0, 0);
                
                // Verifica si la celda está en un período de ausencia
                if (isAbsent(cellDate)) {
                    cell.classList.add('absent');
                    cell.innerHTML = 'X';
                } else {
                    // Marca la celda según la disponibilidad en ese intervalo de 30 minutos
                    if (isSlotAvailable(day, hour, minute)) {
                        cell.classList.add('available');
                    } else {
                        cell.classList.add('not-available');
                        cell.innerHTML = 'X';
                    }
                }
                grid.appendChild(cell);
            }
        }
    }
}

// Función para marcar los turnos (por ejemplo, con la palabra "Turno")
function markAppointments() {
    // Remueve marcas previas
    document.querySelectorAll('.calendar-cell.appointment').forEach(function(cell) {
        cell.classList.remove('appointment');
        cell.innerHTML = '';
    });
    
    let monday = getMonday(currentDate);
    
    appointments.forEach(function(app) {
        let appDate = new Date(app.hora_fecha);
        // Sumar 24 horas para compensar el desfase
        appDate.setTime(appDate.getTime() + 24 * 60 * 60 * 1000);
        
        // Verificamos que el turno esté dentro de la semana mostrada
        if (appDate >= monday && appDate < new Date(monday.getTime() + 7 * 24 * 60 * 60 * 1000)) {
            let dayDiff = Math.floor((appDate - monday) / (24 * 60 * 60 * 1000));
            let hour = appDate.getHours();
            let minute = appDate.getMinutes(); // Debe ser 0 o 30
            let cell = document.querySelector('.calendar-cell[data-day="' + dayDiff + '"][data-hour="' + hour + '"][data-minute="' + minute + '"]');
            if (cell) {
                cell.classList.add('appointment');
                cell.innerHTML = 'Turno';
            }
        }
    });
}



// Variables para el manejo de la semana
const weekRangeElem = document.getElementById('weekRange');
const prevWeekBtn = document.getElementById('prevWeek');
const nextWeekBtn = document.getElementById('nextWeek');
let currentDate = new Date();

function updateCalendarHeader() {
    let monday = getMonday(currentDate);
    let sunday = new Date(monday);
    sunday.setDate(monday.getDate() + 6);
    let options = { day: 'numeric', month: 'short', year: 'numeric' };
    weekRangeElem.textContent = monday.toLocaleDateString('es-ES', options) + ' - ' + sunday.toLocaleDateString('es-ES', options);

    // Actualiza las cabeceras de cada día con su fecha
    const dayHeaders = document.querySelectorAll('.calendar-day-header');
    for (let i = 1; i < dayHeaders.length; i++) {
        let dayDate = new Date(monday);
        dayDate.setDate(monday.getDate() + (i - 1));
        let formattedDate = dayDate.getDate() + '/' + (dayDate.getMonth() + 1);
        let dayName = diasSemana[i - 1];
        dayName = dayName.charAt(0).toUpperCase() + dayName.slice(1);
        dayHeaders[i].innerHTML = dayName + '<br>' + formattedDate;
    }
}


prevWeekBtn.addEventListener('click', function(){
    currentDate.setDate(currentDate.getDate() - 7);
    updateCalendarHeader();
    generateCalendarRows();
    markAppointments();
});

nextWeekBtn.addEventListener('click', function(){
    currentDate.setDate(currentDate.getDate() + 7);
    updateCalendarHeader();
    generateCalendarRows();
    markAppointments();
});

// Inicializa el calendario
generateCalendarRows();
updateCalendarHeader();
markAppointments();
  
// Si se crea un turno, recarga la página para actualizar el calendario
window.addEventListener('turno-created', event => {
    window.location.reload();
});
</script>
</x-filament::page>

