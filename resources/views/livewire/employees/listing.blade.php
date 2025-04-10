<x-employees.layout :title="__('Employees')">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">{{ __('Employees List') }}</h1>
        <flux:modal.trigger name="create-employee">
            <flux:button variant="primary" x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-employee')">
                {{ __('Add Employee') }}
            </flux:button>
        </flux:modal.trigger>
    </div>

     <!-- Campo de búsqueda -->
    <div class="mb-4">
        <input
            wire:model.live="search"
            type="text"
            placeholder="{{ __('Search employees...') }}"
            class="w-full border border-gray-300 rounded-lg p-2"
        />
    </div>
    <!-- Tabla de empleados -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-200 dark:border-neutral-700">
            <thead class="bg-gray-100 dark:bg-neutral-700">
                <tr>
                    <th class="px-4 py-2 border border-gray-200 dark:border-neutral-600">{{ __('Name') }}</th>
                    <th class="px-4 py-2 border border-gray-200 dark:border-neutral-600">{{ __('Email') }}</th>
                    <th class="px-4 py-2 border border-gray-200 dark:border-neutral-600">{{ __('Position') }}</th>
                    <th class="px-4 py-2 border border-gray-200 dark:border-neutral-600">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $employee)
                    <tr>
                        <td class="px-4 py-2 border border-gray-200 dark:border-neutral-600">{{ $employee->name }}</td>
                        <td class="px-4 py-2 border border-gray-200 dark:border-neutral-600">{{ $employee->email }}</td>
                        <td class="px-4 py-2 border border-gray-200 dark:border-neutral-600">{{ $employee->position }}</td>
                        <td class="px-4 py-2 border border-gray-200 dark:border-neutral-600">
                            <flux:modal.trigger :name="'edit-employee-'.$employee->id">
                                <flux:button wire:click="editEmployee({{ $employee->id }})" class="text-blue-500">
                                    {{ __('Edit') }}
                                </flux:button>
                            </flux:modal.trigger>
                            <button x-data @click="confirmDelete({{ $employee->id }})" class="text-red-500">
                                {{ __('Delete') }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">
                            {{ __('No employees found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $employees->links() }}
    </div>

    <!-- Componentes para crear y editar empleados -->
    <livewire:employees.create-employee />
    <div>
        @if($employeeId)
            <livewire:employees.edit-employee :employeeId="$employeeId" :key="'edit-employee-'.$employeeId"/>
        @endif
    </div>

    <!-- Alerta de confirmación -->
    <script>
        // Reemplaza todos los event listeners anteriores con este único listener
        document.addEventListener('livewire:initialized', () => {
            // Listener para notificaciones
            Livewire.on('notify', (data) => {
                Swal.fire({
                    title: data.title,
                    text: data.message,
                    icon: data.type,
                    timer: data.type === 'success' ? 3000 : undefined,
                    timerProgressBar: data.type === 'success',
                });
            });

            // Listener para abrir el modal
            Livewire.on('openEditModal', (data) => {
                setTimeout(() => {
                    Flux.modal('edit-employee-' + data.employeeId).show();
                }, 100);
            });
        });

        // Función para confirmar eliminación
        function confirmDelete(employeeId) {
            Swal.fire({
                title: '{{ __('Are you sure?') }}',
                text: '{{ __('This action cannot be undone.') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __('Yes, delete it!') }}',
                cancelButtonText: '{{ __('Cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.dispatch('deleteEmployee', { id: employeeId });
                    // La notificación será manejada por el backend
                }
            });
        }
    </script>
</x-employees.layout>