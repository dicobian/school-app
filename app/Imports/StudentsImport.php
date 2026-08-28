<?php

namespace App\Imports;

use App\Models\ElementaryStudent;
use App\Models\Classroom;
use Carbon\Carbon;
use DateTimeInterface;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row): ?ElementaryStudent
    {
        // 1. Ambil dan bersihkan NISN
        $nisn = trim((string) ($row['nisn'] ?? ''));

        if ($nisn === '') {
            return null;
        }

        // 2. Ambil nilai kelas dari kolom tingkat_rombel (atau fallback ke kelas/classroom)
        $rawClassroomName = trim((string) (
            $row['tingkat_rombel'] ?? $row['kelas'] ?? $row['classroom'] ?? ''
        ));

        // 3. Pencarian Case-Insensitive ke tabel Classrooms
        $classroom = null;
        if ($rawClassroomName !== '') {
            $classroom = Classroom::whereRaw('LOWER(name) = ?', [mb_strtolower($rawClassroomName)])->first();

            // Jika kelas belum ada di database, otomatis dibuatkan kelas baru
            if (!$classroom) {
                $classroom = Classroom::create(['name' => $rawClassroomName]);
            }
        }

        // 4. Buat atau perbarui data siswa berdasarkan NISN
        return ElementaryStudent::updateOrCreate(
            ['nisn' => $nisn],
            [
                // Foreign key ke tabel classrooms
                'classroom_id'      => $classroom?->id,

                // Menyimpan nama teks tingkat_rombel (misal: "Kelas 1")
                'tingkat_rombel'    => $rawClassroomName ?: $this->value($row, 'tingkat_rombel'),

                // Pemetaan header dari Excel
                'nama'              => $this->value($row, 'nama_lengkap', 'nama'),
                'nik'               => $this->cleanNik($row['nik'] ?? null),
                'tempat_lahir'      => $this->value($row, 'tempat_lahir'),
                'tanggal_lahir'     => $this->dateValue($row['tanggal_lahir'] ?? null),
                'umur'              => $this->value($row, 'umur'),
                'status'            => mb_strtolower($this->value($row, 'status', default: 'aktif')),
                'jenis_kelamin'     => mb_strtolower($this->value($row, 'jenis_kelamin')),
                'alamat'            => $this->value($row, 'alamat'),
                'nomor_telepon'     => $this->value($row, 'no_telepon', 'nomor_telepon'),
                'kebutuhan_khusus'  => $this->value($row, 'kebutuhan_khusus'),
                'disabilitas'       => $this->value($row, 'disabilitas'),
                'nomor_kip_pip'     => $this->value($row, 'nomor_kip_pip'),
                'nama_ayah'         => $this->value($row, 'nama_ayah_kandung', 'nama_ayah'),
                'nama_ibu'          => $this->value($row, 'nama_ibu_kandung', 'nama_ibu'),
                'nama_wali'         => $this->value($row, 'nama_wali'),
            ]
        );
    }

    public function rules(): array
    {
        return [
            'nisn'         => ['required'],
            'nama_lengkap' => ['required_without:nama'],
            'nama'         => ['required_without:nama_lengkap'],
        ];
    }

    private function value(array $row, string $key, ?string $fallback = null, ?string $default = null): ?string
    {
        $value = $row[$key] ?? ($fallback ? ($row[$fallback] ?? null) : null);

        return filled($value) ? trim((string) $value) : $default;
    }

    private function cleanNik(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        // Menghapus tanda petik tunggal bawaan Excel seperti `'360128...`
        return trim(str_replace("'", '', (string) $value));
    }

    private function dateValue(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        try {
            if ($value instanceof DateTimeInterface) {
                return $value->format('Y-m-d');
            }

            if (is_numeric($value)) {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
