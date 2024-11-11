<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;
    // Define los campos asignables en masa
    protected $fillable = [
        'cedula',
        'nombre',
        'email',
        'asignado_id', // Referencia a la asignación
        'area',
        'lider_area',
        'director',
    ];

    // Relación con el modelo Asignacion
    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'asignado_id');
    }

    // Relación con el modelo Peticion (un funcionario puede tener varias peticiones asignadas)
    public function peticiones()
    {
        return $this->hasMany(Peticion::class);
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class);
    }
}
