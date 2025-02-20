<x-filament::page>
    <div class="space-y-6">
        <!-- Calendario con disponibilidad -->
        <div class="grid grid-cols-7 gap-4">
            @foreach($diasSemana as $dia)
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h3 class="font-bold text-lg mb-4">{{ ucfirst($dia) }}</h3>

                    @if($this->getHorariosDia($dia)->count() > 0)
                        <div class="space-y-2">
                            @foreach($this->getHorariosDia($dia) as $horario)
                                <div 
                                    wire:click="editarDisponibilidad({{ $horario['id'] }})"
                                    class="bg-primary-100 dark:bg-gray-700 p-3 rounded-lg cursor-pointer hover:bg-primary-200 transition-colors"
                                >
                                    <div class="font-medium">{{ $horario['inicio'] }} - {{ $horario['fin'] }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ $horario['sucursal'] }} ({{ $horario['sala'] }})
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-gray-500 dark:text-gray-400 italic">
                            No hay disponibilidad registrada
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal de edición (implementado con Alpine.js) -->
    <div x-data="{ open: @entangle('mostrarModalEdicion') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none;">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 relative z-10 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Editar Disponibilidad</h2>
                <button type="button" @click="open = false" class="text-gray-600 hover:text-gray-900">&times;</button>
            </div>

            @if($disponibilidadSeleccionada)
                <form wire:submit.prevent="actualizarDisponibilidad">
                    <div class="space-y-4">
                        <!-- Selección de Sucursal -->
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Sucursal</label>
                            <select class="w-full border-gray-300 rounded" wire:model="disponibilidadSeleccionada.id_sucursal" required>
                                <option value="">Seleccione una sucursal</option>
                                @foreach($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Selección de Sala -->
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Sala</label>
                            <select class="w-full border-gray-300 rounded" wire:model="disponibilidadSeleccionada.id_sala" required>
                                <option value="">Seleccione una sala</option>
                                @foreach($salas as $sala)
                                    <option value="{{ $sala->id }}">{{ $sala->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Hora de inicio -->
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Hora de inicio</label>
                            <input type="time" class="w-full border-gray-300 rounded" wire:model="disponibilidadSeleccionada.horario_inicio" required>
                        </div>

                        <!-- Hora de fin -->
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Hora de fin</label>
                            <input type="time" class="w-full border-gray-300 rounded" wire:model="disponibilidadSeleccionada.horario_fin" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button style="background-color: #007bff;" type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-filament::page>
