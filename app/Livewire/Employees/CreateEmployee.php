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
        try {
            // Validación (mantén tu código actual)
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,email',
                'position' => 'required|string|max:255',
                'salary' => [
                            'required',
                            'numeric',
                            'min:0',
                            'regex:/^\d*(\.\d{1,2})?$/', // Solo permite números con hasta 2 decimales
                            ],
            ]);
    
            // Crear empleado
            Employee::create([
                'name' => $this->name,
                'email' => $this->email,
                'position' => $this->position,
                'salary' => $this->salary,
            ]);
    
            // Reiniciar formulario y cerrar modal
            $this->reset('name', 'email', 'position', 'salary');
            Flux::modal('create-employee')->close();
    
            // Notificar éxito
            $this->dispatch('employeeCreated');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // La validación ya maneja esto automáticamente
            throw $e;
        } catch (\Exception $e) {
            // Para otros errores, notificar al usuario
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => __('Error!'),
                'message' => __('Failed to create employee. Please try again.')
            ]);
        }
    }
}
