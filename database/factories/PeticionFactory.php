<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peticion>
 */
class PeticionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Genera un tipo de petición aleatorio
            'tipo_peticion' => $this->faker->randomElement(['Desembargo', 'Prescripcion', 'Exoneracion']),

            // Genera una fecha de asignación aleatoria en el pasado
            'fecha_asignacion' => $this->faker->dateTimeBetween('now'),

            // ID de un funcionario aleatorio (asegúrate que existan registros en la tabla funcionarios)
            'funcionario_id' => $this->faker->numberBetween(1, 10), // Rango de IDs según los registros en la tabla

            // ID de un contribuyente aleatorio (asegúrate que existan registros en la tabla contribuyentes)
            'contribuyente_id' => $this->faker->numberBetween(1, 10),

            // Fecha de vencimiento posterior a la fecha de asignación
            'fecha_vencimiento' => Carbon::now()->addDays(15)->format('Y-m-d'),

        ];
    }
}
