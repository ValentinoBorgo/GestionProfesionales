<x-filament::page>
<div class="mb-4">
        <input style="width: 100%;" type="text" id="search" placeholder="Buscar por paciente o sucursal..." value="{{ request('search') }}">
    </div>

    <div id="resultados">
        @include('partials.lista_turnos_secretario', ['turnos' => $turnos])
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    // Función para asignar eventos de ordenamiento
    function asignarEventosOrdenamiento() {
        document.querySelectorAll('.btn-ordenar').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault(); // Evita que el enlace recargue la página
                let order = this.getAttribute('data-order'); // Obtiene el tipo de ordenamiento
                fetch("{{ route('buscarpacientesecretario') }}?sort=estado&order=" + order, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('resultados').innerHTML = html;
                    asignarEventosOrdenamiento(); // Reasignar eventos después de actualizar la tabla
                });
            });
        });
    }

    // Asignar eventos de ordenamiento al cargar la página
    asignarEventosOrdenamiento();

    // Búsqueda
    document.getElementById('search').addEventListener('keyup', function () {
        let query = this.value;
        fetch("{{ route('buscarpacientesecretario') }}?search=" + query, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('resultados').innerHTML = html;
            asignarEventosOrdenamiento(); // Reasignar eventos después de actualizar la tabla
        });
    });
});
        
    </script>
</x-filament::page>
