<x-employees.layout :title="__('Employees')">
    <h1 class="text-2xl font-bold">{{ __('Employees List') }}</h1>

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
                            <button wire:click="edit({{ $employee->id }})" class="text-blue-500">{{ __('Edit') }}</button>
                            <button wire:click="delete({{ $employee->id }})" class="text-red-500">{{ __('Delete') }}</button>
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

     <!-- Modal de edición -->
    <flux:modal wire:model.defer="employeeId">
        <form wire:submit.prevent="update" class="space-y-6">
            <!-- Nombre -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name') }}</label>
                <flux:input
                    id="name"
                    wire:model="name"
                    type="text"
                    required
                    class="mt-1 block w-full"
                />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
                <flux:input
                    id="email"
                    wire:model="email"
                    type="email"
                    required
                    class="mt-1 block w-full"
                />
            </div>

            <!-- Posición -->
            <div>
                <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Position') }}</label>
                <flux:input
                    id="position"
                    wire:model="position"
                    type="text"
                    required
                    class="mt-1 block w-full"
                />
            </div>

            <!-- Salario -->
            <div>
                <label for="salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Salary') }}</label>
                <flux:input
                    id="salary"
                    wire:model="salary"
                    type="number"
                    step="0.01"
                    required
                    class="mt-1 block w-full"
                />
            </div>

            <!-- Botones -->
            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                <x-action-message class="me-3" on="employee-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </flux:modal>

    <!-- Alerta de confirmación -->
    <script>
        window.addEventListener('employee-deleted', () => {
            Swal.fire({
                title: '{{ __('Deleted!') }}',
                text: '{{ __('The employee has been deleted.') }}',
                icon: 'success',
            });
        });

        window.addEventListener('employee-updated', () => {
            Swal.fire({
                title: '{{ __('Updated!') }}',
                text: '{{ __('The employee has been updated.') }}',
                icon: 'success',
            });
        });
    </script>
</x-employees.layout>