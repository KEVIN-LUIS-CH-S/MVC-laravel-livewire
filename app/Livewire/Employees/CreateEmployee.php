<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use App\Models\Employee;
use Flux;

class CreateEmployee extends Component
{
    public $name, $email, $position, $salary;

    public function store()
    {
        // Validar los datos del formulario
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'position' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
        ]);

        // Crear el nuevo empleado
        Employee::create([
            'name' => $this->name,
            'email' => $this->email,
            'position' => $this->position,
            'salary' => $this->salary,
        ]);

        // Reiniciar los campos del formulario
        $this->reset('name', 'email', 'position', 'salary');

        // Cerrar el modal usando Flux
        Flux::modal('create-employee')->close();

        // Emitir un evento para notificar al componente padre
        $this->dispatch('employeeCreated');
    }
}
