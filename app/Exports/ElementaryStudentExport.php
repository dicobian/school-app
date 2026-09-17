<?php

namespace App\Exports;

use App\Models\ElementaryStudent;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ElementaryStudentExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{

    protected array $excluded = [
            'created_at',
            'updated_at',
            'barcode'
        ];
    public function collection(): Collection
    {
        return ElementaryStudent::all()->map(function ($student) {
            return collect($student->toArray())
                    ->except($this->excluded)
                    ->all();
        });
    }


    public function headings(): array
    {
        return [
            'id',
            'classroom_id',
            'nama',
            'nisn',
            'nik',
            'tempat_lahir',
            'tanggal_lahir',
            'tingkat_rombel',
            'umur',
            'status',
            'jenis_kelamin',
            'alamat',
            'nomor_telepon',
            'kebutuhan_khusus',
            'disabilitas',
            'nomor_kip_pip',
            'nama_ayah',
            'nama_ibu',
            'nama_wali',
        ];
    }
    public function styles(Worksheet $sheet):  ?array
    {
        return[
            1 => ['font' => ['bold' => true]]
        ];
    }
}
