<x-filament::page>
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <br>
        @if ($errors->any())
        <div style="color: #b91c1c; background-color: #fef2f2; border: 1px solid #fca5a5; border-radius: 5px; padding: 10px; margin-top: 10px;">
            <ul style="margin-left: 20px; list-style-type: disc;">
                @foreach ($errors->all() as $error)
                    <li style="font-weight: bold;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <br>
        <div class="mt-4">
            <x-filament::button type="submit">Crear Turno</x-filament::button>
        </div>
    </form>
</x-filament::page>
