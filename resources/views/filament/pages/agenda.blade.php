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
    </style>

    <div class="mb-4">
        <input type="text" id="search" placeholder="Buscar por paciente, sucursal o estado..." value="{{ request('search') }}">
    </div>

    <div id="resultados">
        @include('partials.lista_turnos', ['turnos' => $turnos])
    </div>

    <script>
        document.getElementById('search').addEventListener('keyup', function() {
            let query = this.value;
            fetch("{{ route('buscarpacienteprofesional') }}?search=" + query, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('resultados').innerHTML = html;
            });
        });
    </script>
</x-filament::page>
