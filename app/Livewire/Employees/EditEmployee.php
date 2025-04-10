<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use App\Models\Employee;
use Flux;

class EditEmployee extends Component
{
    public $employeeId;
    public $name, $email, $position, $salary;

    public function mount($employeeId = null)
    {
        if ($employeeId) {
            $this->employeeId = $employeeId;
            $this->loadEmployee();
        }
    }

    public function loadEmployee()
    {
        if ($this->employeeId) {
            $employee = Employee::findOrFail($this->employeeId);
            $this->name = $employee->name;
            $this->email = $employee->email;
            $this->position = $employee->position;
            $this->salary = $employee->salary;
        }
    }


    public function update()
    {
        try {
            // Validación (mantén tu código actual)
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,email,' . $this->employeeId,
                'position' => 'required|string|max:255',
                'salary' => [
                            'required',
                            'numeric',
                            'min:0',
                            'regex:/^\d*(\.\d{1,2})?$/', // Solo permite números con hasta 2 decimales
                            ],
            ]);
    
            // Verificar que el empleado existe
            if (!$this->employeeId) {
                throw new \Exception(__('Employee not found.'));
            }
    
            // Actualizar empleado
            $employee = Employee::findOrFail($this->employeeId);
            $employee->update([
                'name' => $this->name,
                'email' => $this->email,
                'position' => $this->position,
                'salary' => $this->salary,
            ]);
    
            // Cerrar modal y limpiar
            Flux::modal('edit-employee-' . $this->employeeId)->close();
            $this->reset('employeeId', 'name', 'email', 'position', 'salary');
            
            // Notificar éxito
            $this->dispatch('employeeUpdated');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // La validación ya maneja esto automáticamente
            throw $e;
        } catch (\Exception $e) {
            // Para otros errores, notificar al usuario
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => __('Error!'),
                'message' => $e->getMessage() ?: __('Failed to update employee. Please try again.')
            ]);
        }
    }

    public function render()
    {
        return view('livewire.employees.edit-employee');
    }
}
