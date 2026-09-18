<?php

namespace App\Imports;

use App\Models\Bill;
use App\Models\ElementaryStudent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BillsImport implements ToModel, WithHeadingRow
{
    /** Baris yang gagal di-import (nama siswa tidak ketemu, dll) */
    public array $skippedRows = [];

    public function model(array $row): Model|null
    {
        // 1. Ambil nama siswa dari baris Excel
        $namaSiswa = trim((string) ($row['nama_siswa'] ?? ''));

        if ($namaSiswa === '') {
            $this->skippedRows[] = "Baris tanpa nama siswa dilewati.";
            return null;
        }

        // 2. Cari siswa (case-insensitive)
        $student = ElementaryStudent::whereRaw(
            'LOWER(nama) = ?',
            [mb_strtolower($namaSiswa)]
        )->first();

        // 3. Kalau tidak ketemu, skip baris ini
        if (!$student) {
            $this->skippedRows[] = "Siswa '{$namaSiswa}' tidak ditemukan.";
            return null;
        }

        // 4. Ambil jenis tagihan & hitung nominal dari konstanta
        $namaTagihan = trim((string) ($row['nama_tagihan'] ?? ''));
        $nominal = Bill::NOMINAL_TAGIHAN[$namaTagihan] ?? 0;

        // 5. Normalisasi tanggal bayar (opsional)
        $tanggalBayar = $row['tanggal_bayar'] ?? null;
        if (!empty($tanggalBayar)) {
            try {
                $tanggalBayar = is_numeric($tanggalBayar)
                    ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalBayar)->format('Y-m-d')
                    : Carbon::parse($tanggalBayar)->format('Y-m-d');
            } catch (\Exception $e) {
                $tanggalBayar = null;
            }
        }

        // 6. Buat Bill
        return new Bill([
            'student_id'    => $student->id,
            'nama_tagihan'  => $namaTagihan,
            'tahun_ajaran'  => trim((string) ($row['tahun_ajaran'] ?? '')),
            'bulan'         => trim((string) ($row['bulan'] ?? '')),
            'nominal'       => (int) $nominal,
            'status'        => ($row['status'] ?? 'belum_lunas') === 'lunas' ? 'lunas' : 'belum_lunas',
            'tanggal_bayar' => $tanggalBayar,
        ]);
    }
}
