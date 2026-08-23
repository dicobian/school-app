<?php

namespace Database\Factories;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Classroom>
 */
class ClassroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Kelas ' . fake()->unique()->randomElement(['1', '2', '3', '4', '5', '6']),
            'deskripsi' => fake()->sentence(8),
        ];
    }
}
