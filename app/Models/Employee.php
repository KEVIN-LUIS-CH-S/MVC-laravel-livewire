<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'position',
        'salary',
        'created_by',
        'updated_by',
    ];
    
    /**
     * Relación con el modelo User (usuario que creó el registro).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    /**
     * Relación con el modelo User (usuario que actualizó el registro).
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
