<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\ElementaryStudent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ElementaryStudent>
 */
class ElementaryStudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'classroom_id' => Classroom::factory(),
            'nama' => fake()->name(),
            'nisn' => fake()->unique()->numerify('##########'),
            'nik' => fake()->unique()->numerify('################'),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->dateTimeBetween('-15 years', '-6 years')->format('Y-m-d'),
            'tingkat_rombel' => fake()->randomElement(['1', '2', '3', '4', '5', '6']),
            'umur' => fake()->numberBetween(6, 15),
            'status' => fake()->randomElement(['aktif', 'tidak aktif']),
            'jenis_kelamin' => fake()->randomElement(['laki-laki', 'perempuan']),
            'alamat' => fake()->address(),
            'nomor_telepon' => fake()->phoneNumber(),
            'kebutuhan_khusus' => fake()->randomElement(['Tidak Ada', 'Kesulitan Belajar']),
            'disabilitas' => fake()->randomElement(['Tidak', 'Ya']),
            'nomor_kip_pip' => fake()->unique()->numerify('##########'),
            'nama_ayah' => fake()->name('male'),
            'nama_ibu' => fake()->name('female'),
            'nama_wali' => fake()->name(),
        ];
    }
}
