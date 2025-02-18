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
        }

        td {
            border-bottom: 1px solid #ddd;
        }

        tbody tr:hover {
            background-color: #f9f9f9;
            color: black;
        }

        .btn-gestion {
            display: inline-block;
            padding: 8px 16px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-gestion:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .btn-gestion:active {
            background-color: #1e7e34;
        }

        #search {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        #search:focus {
            border-color: #28a745;
            outline: none;
        }
    </style>

       <!-- Campo de búsqueda -->
       <div class="mb-4">
        <input type="text" id="search" placeholder="Buscar por nombre o apellido..." value="{{ request('search') }}">
    </div>

    <!-- Tabla de fichas médicas -->
    <table>
        <thead>
            <tr>
                <th>Nombre y Apellido</th>
                <th>Código de Ficha Médica</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="resultados">
            @include('partials.lista_fichas', ['fichasMedicas' => $fichasMedicas])
        </tbody>
    </table>

    <!-- Script para manejar la búsqueda -->
    <script>
        document.getElementById('search').addEventListener('keyup', function () {
            let query = this.value; // Obtiene el valor del campo de búsqueda
            fetch("{{ route('buscarFichasMedicas') }}?search=" + query, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('resultados').innerHTML = html; // Actualiza la tabla con los resultados
            });
        });
    </script>
</x-filament::page>