<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contribuyente>
 */
class ContribuyenteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             // Genera un número único de 10 dígitos para simular la cédula
             'cedula' => $this->faker->unique()->numberBetween(1000000000, 9999999999),

             // Genera un nombre ficticio
             'nombre' => $this->faker->name,
 
             // Genera un email ficticio y único
             'email' => $this->faker->unique()->safeEmail,
 
             // Genera una matrícula ficticia con letras y números
             'matricula' => strtoupper($this->faker->bothify('??###')) // Ej: AB123
        ];
    }
}
