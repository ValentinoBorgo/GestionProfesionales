<x-filament::page>
    <form wire:submit.prevent="submit">
        {{ $this->form }}
        <div class="mt-4">
            <br>
            <x-filament::button type="submit">
                Dar de Alta Paciente
            </x-filament::button>
        </div>
    </form>
</x-filament::page>


