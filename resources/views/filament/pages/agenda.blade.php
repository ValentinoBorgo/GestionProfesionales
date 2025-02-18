<x-filament::page>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
            color: black;
            cursor: pointer;
        }

        td {
            border-bottom: 1px solid #ddd;
        }

        tbody tr:hover {
            background-color: #f9f9f9;
            color: black;
        }

        .fila-cancelada {
            background-color: #ffcccc !important;
            color: black;
        }

        .btn-gestion, .btn-cancelar, .btn-revertir {
            display: inline-block;
            padding: 8px 16px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-gestion { background-color: #28a745; }
        .btn-gestion:hover { background-color: #218838; transform: scale(1.05); }

        .btn-cancelar { background-color: #dc3545; margin-left: 10px; }
        .btn-cancelar:hover { background-color: #c82333; transform: scale(1.05); }

        .btn-revertir { background-color: rgb(51, 255, 0); margin-left: 10px; }
        .btn-revertir:hover { background-color: rgb(35, 200, 35); transform: scale(1.05); }

        .estado-cancelado {
            font-weight: bold;
            color: red;
        }
        .btn-ordenar {
    color: #007bff;
    text-decoration: none;
    font-size: 12px;
    margin: 0 5px;
    cursor: pointer;
}

.btn-ordenar:hover {
    text-decoration: underline;
}
    </style>

    <div class="mb-4">
        <input style="width: 100%;" type="text" id="search" placeholder="Buscar por paciente o sucursal..." value="{{ request('search') }}">
    </div>

    <div id="resultados">
        @include('partials.lista_turnos', ['turnos' => $turnos])
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    // Función para asignar eventos de ordenamiento
    function asignarEventosOrdenamiento() {
        document.querySelectorAll('.btn-ordenar').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault(); // Evita que el enlace recargue la página
                let order = this.getAttribute('data-order'); // Obtiene el tipo de ordenamiento
                fetch("{{ route('buscarpacienteprofesional') }}?sort=estado&order=" + order, {
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
        fetch("{{ route('buscarpacienteprofesional') }}?search=" + query, {
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
