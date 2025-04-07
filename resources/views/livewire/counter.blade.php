<!-- filepath: c:\xampp\htdocs\MVC2-laravel\resources\views\livewire\counter.blade.php -->
<div class="flex flex-col items-center justify-center h-full gap-4">
    <!-- Título del contador -->
    <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200">
        Counter: <span class="text-blue-500">{{ $count }}</span>
    </h1>

    <!-- Botones de incremento y decremento -->
    <div class="flex gap-4">
        <flux:button wire:click="decrement" variant="danger" class="px-4 py-2">
            -
        </flux:button>
        <flux:button wire:click="increment" variant="primary" class="px-4 py-2">
            +
        </flux:button>
    </div>
</div>