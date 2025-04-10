<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;
use Flux;

class Listing extends Component
{
    use WithPagination;

    public $search = ''; // Campo de búsqueda
    public $employeeId; // ID del empleado seleccionado para editar o eliminar

    protected $queryString = ['search']; // Persistencia de la búsqueda en la URL
    protected $listeners = [
                            'employeeCreated' => 'handleEmployeeCreated',
                            'employeeUpdated' => 'handleEmployeeUpdated',
                            'deleteEmployee' => 'deleteEmployee'
                            ];
    

    public function updatingSearch()
    {
        $this->resetPage(); // Reinicia la paginación al buscar
    }

    public function showEmployeeCreatedAlert()
    {
        $this->dispatch('employee-created');
    }

    public function editEmployee($id)
    {
        $this->employeeId = $id;
        $this->dispatch('openEditModal', employeeId: $id);  
    }

    public function handleEmployeeUpdated()
    {
        $this->reset('employeeId'); // Limpiar el ID después de actualizar
        $this->notify('success', __('Updated!'), __('The employee has been updated successfully.'));
    }

    public function handleEmployeeCreated()
    {
        $this->notify('success', __('Success!'), __('The employee has been created successfully.'));
    }

    public function deleteEmployee($id)
    {
        try {
            Employee::findOrFail($id)->delete();
            $this->notify('success', __('Deleted!'), __('The employee has been deleted successfully.'));
        } catch (\Exception $e) {
            $this->notify('error', __('Error!'), __('Failed to delete the employee. Please try again.'));
        }
    }

    public function notify($type, $title, $message)
    {
        $this->dispatch('notify', type: $type, title: $title, message: $message);
    }

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
}