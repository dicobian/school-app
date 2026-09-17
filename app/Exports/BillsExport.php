<?php

namespace App\Exports;

use App\Models\Bill;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class BillsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles {

    public function query(): Builder
    {
        return Bill::query()
            ->with(['students.classroom'])
            ->orderBy('created_at');
    }

    public function headings(): array
    {
        return [
            'Time Stamp',
            'Nama Siswa',
            'Kelas',
            'Nama Tagihan',
            'Tahun Ajaran',
            'Bulan',
            'Nominal',
            'Status',
            'Tanggal Bayar',
        ];
    }

    public function map($bill): array
    {
        return [
            $bill->created_at,
            $bill->students->nama ?? '-',
            $bill->students->classroom->name ?? '-',
            $bill->nama_tagihan,
            $bill->tahun_ajaran,
            $bill->bulan,
            $bill->nominal,
            $bill->status === 'lunas' ? 'Lunas' : 'Belum Lunas',
            $bill->tanggal_bayar
                ? \Carbon\Carbon::parse($bill->tanggal_bayar)->format('d-m-Y')
                : '-',
        ];
    }

    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }
}

/**
 * ============================================================================
 *  BillsExport — Export data tagihan siswa ke Excel
 * ============================================================================
 *
 *  Import & kegunaannya:
 *
 *  - App\Models\Bill
 *      Model sumber data yang akan diekspor.
 *
 *  - Illuminate\Database\Eloquent\Builder
 *      Return type untuk method query(), supaya kompatibel dengan interface
 *      FromQuery dari maatwebsite/excel.
 *
 *  - Maatwebsite\Excel\Concerns\FromQuery
 *      Menandakan data diambil dari query builder (bukan Collection / array).
 *      Lebih efisien untuk data besar karena tidak load semua ke memory.
 *
 *  - Maatwebsite\Excel\Concerns\WithHeadings
 *      Menambahkan baris judul (header) di baris pertama file Excel.
 *      Isinya diambil dari method headings().
 *
 *  - Maatwebsite\Excel\Concerns\WithMapping
 *      Memungkinkan transformasi tiap baris sebelum ditulis ke Excel
 *      (mengubah nilai, format tanggal, ambil data relasi, dll).
 *      Logikanya ada di method map().
 *
 *  - Maatwebsite\Excel\Concerns\ShouldAutoSize
 *      Otomatis menyesuaikan lebar kolom dengan isi terpanjangnya,
 *      supaya tidak perlu atur lebar kolom manual.
 *
 *  - Maatwebsite\Excel\Concerns\WithStyles
 *      Memberi kemampuan menambahkan style (bold, warna, border, dll)
 *      ke cell. Style ditulis di method styles().
 *
 *  - PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
 *      Class dari PhpSpreadsheet (library dasar di balik maatwebsite/excel).
 *      Dipakai sebagai parameter method styles() untuk akses cell/row/column.
 *
 *  implements:
 *  - FromQuery      → wajib method query()
 *  - WithHeadings   → wajib method headings()
 *  - WithMapping    → wajib method map()
 *  - ShouldAutoSize → marker (tanpa method)
 *  - WithStyles     → wajib method styles()
 * ============================================================================
 */



