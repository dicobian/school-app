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

class ElementaryStudentExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithMapping
{

    protected array $excluded = [
            'created_at',
            'updated_at',
            'barcode',
            'id',
            'classroom_id'
        ];
    protected $rowNumber = 0;
    public function collection(): Collection
    {
        return ElementaryStudent::all();
    }

    public function map($student): array
    {
        $this->rowNumber++;
        $data = collect($student->toArray())
                ->except($this->excluded)
                ->values()
                ->all();
        return array_merge([$this->rowNumber], $data);
    }

    public function headings(): array
    {
        return [
            'No',
            // 'id',
            // 'classroom_id',
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
