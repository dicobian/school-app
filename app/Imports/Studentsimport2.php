<?php

namespace App\Imports;

use App\Models\Classroom;
use App\Models\ElementaryStudent;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class Studentsimport2 implements ToModel, WithHeadingRow
{
    public function model(array $row): Model|null
    {
        if (blank($row['nama'] ?? null) || blank($row['tingkat_rombel'] ?? null))
        {
        return null;
        }

        $namaKelas = mb_strtolower($row['tingkat_rombel']);
        $kelas = Classroom::firstOrCreate(
            ['name' => $namaKelas],
            ['deskripsi' => '-']);

        return new ElementaryStudent([
            'classroom_id' => $kelas->id,
            'nama' => $row['nama'],
            'nisn' => $row['nisn'],
            'nik' => $row['nik'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => $row['tanggal_lahir'],
            'tingkat_rombel' => $row['tingkat_rombel'],
            'umur' => $row['umur'],
            'status' => $row['status'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'alamat' => $row['alamat'],
            'nomor_telepon' => $row['nomor_telepon'],
            'kebutuhan_khusus' => $row['kebutuhan_khusus'],
            'disabilitas' => $row['disabilitas'],
            'nomor_kip_pip' => $row['nomor_kip_pip'],
            'nama_ayah' => $row['nama_ayah'],
            'nama_ibu' => $row['nama_ibu'],
            'nama_wali' => $row['nama_wali'],
        ]);
    }

}
