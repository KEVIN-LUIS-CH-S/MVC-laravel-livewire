<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;
use Flux;

class Listing extends Component
{
    use WithPagination;


    // Propiedades del componente
    public $search = ''; // Campo de búsqueda
    public $employeeId; // ID del empleado seleccionado para editar o eliminar

    // Configuracion del componente
    protected $queryString = ['search']; // Persistencia de la búsqueda en la URL

    // Listeners para eventos de otros componentes
    protected $listeners = [
                            'employeeCreated' => 'handleEmployeeCreated',
                            'employeeUpdated' => 'handleEmployeeUpdated',
                            'deleteEmployee' => 'deleteEmployee'
                            ];
    
    // Metodos del ciclo de vida
    public function updatingSearch()
    {
        $this->resetPage(); // Reinicia la paginación al buscar
    }

    // Renderiza la vista con los empleados filtrados
    public function render()
    {
        return view('livewire.employees.listing', [
            'employees' => Employee::query()
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%')
                          ->orWhere('position', 'like', '%' . $this->search . '%');
                })
                ->orderBy('name')
                ->paginate(10),
        ]);
    }

    // Metodos CRUD
    public function editEmployee($id) //Prepara la edición de un empleado
    {
        $this->employeeId = $id;
    }

    public function deleteEmployee($id) // Elimina un empleado
    {
        try {
            Employee::findOrFail($id)->delete();
            $this->notify('success', __('Deleted!'), __('The employee has been deleted successfully.'));
        } catch (\Exception $e) {
            $this->notify('error', __('Error!'), __('Failed to delete the employee. Please try again.'));
        }
    }

    // Manejadores de eventos
    public function handleEmployeeUpdated() //Maneja la respuesta después de actualizar un empleado
    {
        $this->reset('employeeId'); // Limpiar el ID después de actualizar
        $this->notify('success', __('Updated!'), __('The employee has been updated successfully.'));
    }

    public function handleEmployeeCreated() //Maneja la respuesta después de crear un empleado
    {
        $this->notify('success', __('Success!'), __('The employee has been created successfully.'));
    }

    // Metodos de utilidad
    public function notify($type, $title, $message)
    {
        $this->dispatch('notify', type: $type, title: $title, message: $message);
    }

}