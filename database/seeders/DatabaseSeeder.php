<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\ElementaryStudent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin Filament jika belum ada
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Buat 5 Kelas, dan tiap 1 Kelas otomatis berisi 15 Siswa
        Classroom::factory()
            ->count(5)
            ->has(ElementaryStudent::factory()->count(15), 'students') // 'students' adalah nama fungsi relasi hasMany di Model Classroom
            ->create();
    }
}
