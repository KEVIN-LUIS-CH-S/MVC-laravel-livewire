<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); // ID único para cada empleado
            $table->string('name'); // Nombre del empleado
            $table->string('email')->unique(); // Correo electrónico único
            $table->string('position'); // Puesto del empleado
            $table->decimal('salary', 10, 2); // Salario del empleado
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete(); // Usuario que creó el registro
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete(); // Usuario que actualizó el registro
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
