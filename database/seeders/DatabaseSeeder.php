<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
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
                'password' => Hash::make('123'),
            ]
        );
        for($i = 1; $i <= 6; $i++){
            Classroom::create([
                'name'      => 'kelas ' . $i,
                'tingkat'   => $i
            ]);
        }

        AcademicYear::create([
            'name' => '2026/2027',
            'status' => 'aktif',
            'tanggal_mulai' => '2026-07-13', // Mulai tahun ajaran baru (biasanya pertengahan Juli)
            'tanggal_selesai' => '2027-06-25' // Berakhir di akhir Juni tahun berikutnya
        ]);

        AcademicYear::create([
            'name' => '2027/2028',
            'status' => 'akan_datang',
            'tanggal_mulai' => '2027-07-12', // Tahun ajaran berikutnya
            'tanggal_selesai' => '2028-06-23'
        ]);
        // 2. Buat 5 Kelas, dan tiap 1 Kelas otomatis berisi 15 Siswa
        // Classroom::factory()
        //     ->count(5)
        //     ->has(ElementaryStudent::factory()->count(15), 'students') // 'students' adalah nama fungsi relasi hasMany di Model Classroom
        //     ->create();
    }
}
