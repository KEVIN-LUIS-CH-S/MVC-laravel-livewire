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
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $this->employeeId,
            'position' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
        ]);

        // Verificar que el empleado existe antes de actualizar
        if ($this->employeeId) {
            $employee = Employee::findOrFail($this->employeeId);
            $employee->update([
                'name' => $this->name,
                'email' => $this->email,
                'position' => $this->position,
                'salary' => $this->salary,
            ]);

             // Cerrar el modal usando Flux
            Flux::modal('edit-employee-' . $this->employeeId)->close();

            // Reiniciar los campos del formulario y cerrar el modal
            $this->reset('employeeId', 'name', 'email', 'position', 'salary');
            $this->dispatch('employee-updated');
        }
    }

    public function render()
    {
        return view('livewire.employees.edit-employee');
    }
}
