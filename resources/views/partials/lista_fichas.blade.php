@forelse ($fichasMedicas as $ficha)
    <tr>
        <td>{{ $ficha->nombre }} {{ $ficha->apellido }}</td>
        <td>{{ $ficha->id }}</td>
        <td>
            <a href="{{ route('fichaMedica.show', $ficha->id) }}" class="btn-gestion">Gestionar Ficha Médica</a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3">No se encontraron resultados.</td>
    </tr>
@endforelse