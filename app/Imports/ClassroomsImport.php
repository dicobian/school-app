<?php

namespace App\Imports;

use App\Models\Classroom;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ClassroomsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row): ?Classroom
    {
        $name = trim((string) ($row['name'] ?? $row['nama_kelas'] ?? ''));

        if ($name === '') {
            return null;
        }

        // Mencegah duplikat berdasarkan kolom 'name' (case-insensitive)
        return Classroom::updateOrCreate(
            ['name' => $name],
            [
                'deskripsi' => trim((string) ($row['deskripsi'] ?? $row['description'] ?? '-')),
            ]
        );
    }

    public function rules(): array
    {
        return [
            'name' => ['required_without:nama_kelas'],
        ];
    }
}
