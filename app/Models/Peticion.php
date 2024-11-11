<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peticion extends Model
{
    use HasFactory;

        // Define los campos asignables en masa (mass assignable)
        protected $fillable = [
            'tipo_peticion',
            'fecha_asignacion',
            'funcionario_id',
            'contribuyente_id',
            'fecha_vencimiento',
        ];
    
        // Relación con el modelo Funcionario
        public function funcionario()
        {
            return $this->belongsTo(Funcionario::class);
        }
    
        // Relación con el modelo Contribuyente
        public function contribuyente()
        {
            return $this->belongsTo(Contribuyente::class);
        }
}
