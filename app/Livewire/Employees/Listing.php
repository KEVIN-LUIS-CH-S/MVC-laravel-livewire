<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $search = ''; // Campo de búsqueda
    public $employeeId; // ID del empleado seleccionado para editar o eliminar
    public $name, $email, $position, $salary; // Campos para editar

    protected $queryString = ['search']; // Persistencia de la búsqueda en la URL

    public function updatingSearch()
    {
        $this->resetPage(); // Reinicia la paginación al buscar
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $this->employeeId = $employee->id;
        $this->name = $employee->name;
        $this->email = $employee->email;
        $this->position = $employee->position;
        $this->salary = $employee->salary;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $this->employeeId,
            'position' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
        ]);

        $employee = Employee::findOrFail($this->employeeId);
        $employee->update([
            'name' => $this->name,
            'email' => $this->email,
            'position' => $this->position,
            'salary' => $this->salary,
        ]);

        $this->reset(['employeeId', 'name', 'email', 'position', 'salary']);
        $this->dispatch('employee-updated');
    }

    public function delete($id)
    {
        Employee::findOrFail($id)->delete();
        $this->dispatch('employee-deleted');
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