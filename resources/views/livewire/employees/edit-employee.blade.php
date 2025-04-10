<flux:modal :name="'edit-employee-'.$employeeId" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
    <form wire:submit.prevent="update" class="space-y-6">
        <!-- Título -->
        <div>
            <flux:heading size="lg">{{ __('Edit Employee') }}</flux:heading>
            <flux:subheading>{{ __('Update the employee information below.') }}</flux:subheading>
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
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
        <div class="flex justify-end space-x-2 rtl:space-x-reverse">
            <flux:modal.close>
                <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="primary" type="submit">{{ __('Update') }}</flux:button>
        </div>
    </form>
</flux:modal>