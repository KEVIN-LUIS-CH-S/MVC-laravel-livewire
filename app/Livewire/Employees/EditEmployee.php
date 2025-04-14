<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use App\Models\Employee;
use Flux;

class EditEmployee extends Component
{

    // Propiedades del formulario
    public $employeeId;
    public $name, $email, $position, $salary;

    // Metodos del ciclo de vida
    public function mount($employeeId = null) //Inicializa el componente con los datos del empleado
    {
        if ($employeeId) {
            $this->employeeId = $employeeId;
            $this->loadEmployee($employeeId);
        }
    }

    // Metodos de acceso a Datos
    public function loadEmployee($employeeId) //Carga los datos del empleado desde la base de datos
    {
        if ($this->employeeId) {
            $employee = Employee::findOrFail($this->employeeId);
            $this->name = $employee->name;
            $this->email = $employee->email;
            $this->position = $employee->position;
            $this->salary = $employee->salary;

            // Notifica que los datos están listos para mostrar el modal
            $this->dispatch('modal-ready', id: $employeeId);
        }
        
    }

    // Metodos de validacion

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $this->employeeId,
            'position' => 'required|string|max:255',
            'salary' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d*(\.\d{1,2})?$/', // Solo permite números con hasta 2 decimales
            ],
        ];
    }

    // Metodos de accion
    public function update() //Actualiza los datos del empleado
    {
        try {
            // Validacion
            $this->validate($this->rules());
    
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
}
