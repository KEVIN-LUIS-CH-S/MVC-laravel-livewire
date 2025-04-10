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
    }

    public function handleEmployeeUpdated()
    {
        $this->reset('employeeId'); // Limpiar el ID después de actualizar
        $this->dispatch('employee-updated'); // Disparar el evento para la notificación
    }

    public function handleEmployeeCreated()
    {
        $this->refreshEmployees();
        $this->showEmployeeCreatedAlert();
    }

    public function deleteEmployee($id)
    {
        Employee::findOrFail($id)->delete();
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

    public function refreshEmployees()
    {
        // Este método se ejecutará cuando se emita el evento 'employeeCreated'
        $this->render();
    }
}