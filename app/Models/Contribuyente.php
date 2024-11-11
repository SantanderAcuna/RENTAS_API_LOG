<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribuyente extends Model
{
    use HasFactory;

     // Define los campos asignables en masa
     protected $fillable = [
        'cedula',
        'nombre',
        'email',
        'matricula',
    ];

    // Relación con el modelo Peticion (un contribuyente puede tener varias peticiones)
    public function peticiones()
    {
        return $this->hasMany(Peticion::class);
    }
}
