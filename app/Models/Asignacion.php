<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    use HasFactory;

    
     // Campos asignables en masa
     protected $fillable = [
        'nombre_asignacion',
        'descripcion',
    ];

    // Relación con el modelo Funcionario (una asignación puede tener varios funcionarios asignados)
    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class);
    }

    
}
