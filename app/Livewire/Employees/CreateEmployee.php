<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use App\Models\Employee;
use Illuminate\Support\Facades\Http;
use Flux;

class CreateEmployee extends Component
{
    public $dni = '';
    public $name = '', $email = '', $position = '', $salary = '';
    public $isLoading = false;
    public $dniError = '';

    // Se ejecuta cuando cambia el valor del DNI
    public function updatedDni()
    {
        $this->resetValidation('dni');
        $this->dniError = '';
        
        if (strlen($this->dni) === 8) {
            $this->fetchDataByDni();
        }
    }

    // Método para consultar la API de Perú
    public function fetchDataByDni()
    {
        if (!is_numeric($this->dni) || strlen($this->dni) !== 8) {
            $this->dniError = 'El DNI debe tener 8 dígitos numéricos';
            return;
        }

        $this->isLoading = true;
        
        try {
            // Preparamos los parámetros y hacemos la solicitud
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.apiperu.token'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post(config('services.apiperu.url') . '/dni', [
                'dni' => $this->dni
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['success']) && $data['success']) {
                    // Asignamos el nombre completo
                    $this->name = $data['data']['nombre_completo'] ?? '';
                    
                    // Generamos un email sugerido basado en el nombre
                    if (!empty($this->name)) {
                        $nameParts = explode(' ', $this->name);
                        $firstName = strtolower($nameParts[0]);
                        $lastName = strtolower(end($nameParts));
                        $this->email = $firstName . '.' . $lastName . '@empresa.com';
                    }
                } else {
                    $this->dniError = $data['message'] ?? 'No se encontraron datos para este DNI';
                }
            } else {
                $this->dniError = 'Error en la consulta: ' . $response->status();
            }
        } catch (\Exception $e) {
            $this->dniError = 'Error al conectar con el servicio: ' . $e->getMessage();
        } finally {
            $this->isLoading = false;
        }
    }

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
