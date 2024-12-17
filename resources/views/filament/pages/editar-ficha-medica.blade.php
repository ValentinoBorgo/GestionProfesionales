<x-filament::page>
    {{ $this->form }}

    <div class="flex space-x-4 mt-6">
        <x-filament::button wire:click="save" color="primary">Actualizar Ficha</x-filament::button>
        
        <a href="{{ route('pacientes.index') }}" 
           class="inline-block px-4 py-2 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600 hover:shadow-lg transition duration-300 ease-in-out">
            Volver a la lista de pacientes
        </a>
    </div>
</x-filament::page>
