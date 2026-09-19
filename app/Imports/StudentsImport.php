<?php

namespace App\Imports;

use App\Models\Classroom;
use App\Models\ElementaryStudent;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class Studentsimport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row): Model|null
    {
        $nama = $this->clean($row['nama'] ?? null);
        $tingkatRombel = $this->clean($row['tingkat_rombel'] ?? null);

        if (blank($nama) || blank($tingkatRombel)) {
            return null;
        }

        // Cari atau buat kelas berdasarkan tingkat_rombel
        $namaKelas = mb_strtolower($tingkatRombel);
        $kelas = Classroom::firstOrCreate(
            ['name' => $namaKelas],
            ['deskripsi' => '-']
        );

        // Cari siswa yang SUDAH ADA berdasarkan nama (case-insensitive).
        // Kalau ketemu → nanti di-UPDATE. Kalau tidak ketemu → dibuat baru.
        $student = ElementaryStudent::whereRaw('LOWER(nama) = ?', [mb_strtolower($nama)])
            ->first() ?? new ElementaryStudent();

        $student->fill([
            'classroom_id'     => $kelas->id,
            'nama'             => $nama,
            'nisn'             => $this->cleanNumericText($row['nisn'] ?? null),
            'nik'              => $this->cleanNumericText($row['nik'] ?? null),
            'tempat_lahir'     => $this->clean($row['tempat_lahir'] ?? null),
            'tanggal_lahir'    => $this->clean($row['tanggal_lahir'] ?? null),
            'tingkat_rombel'   => $tingkatRombel,
            'umur'             => $this->clean($row['umur'] ?? null),
            'status'           => $this->clean($row['status'] ?? null),
            'jenis_kelamin'    => $this->clean($row['jenis_kelamin'] ?? null),
            'alamat'           => $this->clean($row['alamat'] ?? null),
            'nomor_telepon'    => $this->cleanNumericText($row['nomor_telepon'] ?? null),
            'kebutuhan_khusus' => $this->clean($row['kebutuhan_khusus'] ?? null),
            'disabilitas'      => $this->clean($row['disabilitas'] ?? null),
            'nomor_kip_pip'    => $this->cleanNumericText($row['nomor_kip_pip'] ?? null),
            'nama_ayah'        => $this->clean($row['nama_ayah'] ?? null),
            'nama_ibu'         => $this->clean($row['nama_ibu'] ?? null),
            'nama_wali'        => $this->clean($row['nama_wali'] ?? null),
        ]);

        return $student;
    }

    /**
     * Bersihkan teks biasa: buang spasi di awal/akhir,
     * dan rapikan spasi ganda di tengah jadi 1 spasi.
     */
    private function clean(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $value = trim((string) $value);
        $value = preg_replace('/\s+/', ' ', $value);

        return $value === '' ? null : $value;
    }

    /**
     * Khusus untuk kolom "angka sebagai teks" (NIK, NISN, No. Telepon, KIP/PIP):
     * buang tanda kutip satu (') bawaan Excel, lalu bersihkan spasi seperti biasa.
     */
    private function cleanNumericText(mixed $value): ?string
    {
        $value = $this->clean($value);

        if (blank($value)) {
            return null;
        }

        // Buang tanda kutip satu di manapun posisinya (awal/tengah/akhir)
        $value = str_replace("'", '', $value);

        return trim($value) === '' ? null : trim($value);
    }
}
