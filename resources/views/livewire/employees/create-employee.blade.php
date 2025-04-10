<flux:modal name="create-employee" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
    <form wire:submit.prevent="store" class="space-y-6">
        <!-- Título -->
        <div>
            <flux:heading size="lg">{{ __('Add New Employee') }}</flux:heading>
            <flux:subheading>{{ __('Fill in the details below to add a new employee.') }}</flux:subheading>
        </div>

         <!-- DNI (Nuevo campo) -->
        <div>
            <label for="dni" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('DNI') }}</label>
            <div class="relative">
                <flux:input
                    id="dni"
                    wire:model.live.debounce.500ms="dni"
                    type="text"
                    maxlength="8"
                    placeholder="Ingresa 8 dígitos"
                    class="mt-1 block w-full"
                />
                @if($isLoading)
                    <div class="absolute right-3 top-3">
                        <flux:icon.loading class="h-5 w-5 text-blue-500" />
                    </div>
                @endif
            </div>
            @if($dniError)
                <span class="text-red-500 text-sm">{{ $dniError }}</span>
            @endif
        </div>

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
            <flux:error name="email" />
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
            <flux:error name="salary" />
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-2 rtl:space-x-reverse">
            <flux:modal.close>
                <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
        </div>
    </form>
</flux:modal>