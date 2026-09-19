<?php

namespace App\Exports;

use App\Models\Bill;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;


class FormatTagihan implements
    FromArray,
    WithHeadings,
    ShouldAutoSize,
    WithStyles,
    WithEvents
{

    public function array(): array
    {
        return [];
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


    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;




                // ============================================
                // Dropdown untuk Kolom "Nama Tagihan" (Kolom D)
                // ============================================

                // Ambil daftar jenis tagihan dari konstanta di model Bill
                $jenisTagihan = array_keys(Bill::NOMINAL_TAGIHAN);
                $bulan = array_keys(Bill::BULAN);

                // Buat validation untuk cell D2
                $validationTagihan = $sheet->getCell('D2')->getDataValidation();
                $validationTagihan->setType(DataValidation::TYPE_LIST);
                $validationTagihan->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validationTagihan->setAllowBlank(false);
                $validationTagihan->setShowInputMessage(true);
                $validationTagihan->setShowErrorMessage(true);
                $validationTagihan->setShowDropDown(true);
                $validationTagihan->setErrorTitle('Input error');
                $validationTagihan->setError('Value is not in list.');
                $validationTagihan->setPromptTitle('Pilih Jenis Tagihan');
                $validationTagihan->setPrompt('Silakan pilih dari daftar.');
                // Gabungkan opsi menjadi string dipisahkan koma
                $validationTagihan->setFormula1('"' . implode(',', $jenisTagihan) . '"');

                // Terapkan validation ke seluruh kolom D (dari baris 2 sampai 1000)
                $validationTagihan->setSqref('D2:D1000');

                // ============================================
                // Dropdown untuk BULAN
                // ============================================
                $validationBulan = $sheet->getCell('F2')->getDataValidation();
                $validationBulan->setType(DataValidation::TYPE_LIST);
                $validationBulan->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validationBulan->setAllowBlank(false);
                $validationBulan->setShowInputMessage(true);
                $validationBulan->setShowErrorMessage(true);
                $validationBulan->setShowDropDown(true);
                $validationBulan->setErrorTitle('Input error');
                $validationBulan->setError('Value is not in list.');
                $validationBulan->setPromptTitle('Pilih Bulan');
                $validationBulan->setPrompt('Silakan pilih dari daftar.');
                // Gabungkan opsi menjadi string dipisahkan koma
                $validationBulan->setFormula1('"' . implode(',', $bulan) . '"');

                // Terapkan validation ke seluruh kolom bulan (dari baris 2 sampai 1000)
                $validationBulan->setSqref('F2:F1000');

                // ============================================
                // Dropdown untuk Kolom "Status" (Kolom H)
                // ============================================
                $statusOptions = ['lunas', 'belum_lunas'];

                $validationStatus = $sheet->getCell('H2')->getDataValidation();
                $validationStatus->setType(DataValidation::TYPE_LIST);
                $validationStatus->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validationStatus->setAllowBlank(false);
                $validationStatus->setShowInputMessage(true);
                $validationStatus->setShowErrorMessage(true);
                $validationStatus->setShowDropDown(true);
                $validationStatus->setErrorTitle('Input error');
                $validationStatus->setError('Value is not in list.');
                $validationStatus->setPromptTitle('Pilih Status');
                $validationStatus->setPrompt('Silakan pilih dari daftar.');
                $validationStatus->setFormula1('"' . implode(',', $statusOptions) . '"');

                // Terapkan validation ke seluruh kolom H (dari baris 2 sampai 1000)
                $validationStatus->setSqref('H2:H1000');


                // ============================================
                // 1. BUAT SHEET REFERENSI TERSEMBUNYI
                // ============================================
                //untuk automatis nominal terisi ketika jenis tagihan terpilih
                $spreadsheet = $sheet->getParent();
                $refSheet = $spreadsheet->createSheet();
                $refSheet->setTitle('Ref');

                // Header
                $refSheet->setCellValue('A1', 'Kode Tagihan');
                $refSheet->setCellValue('B1', 'Nominal');

                // Isi data dari konstanta Bill
                $row = 2;
                foreach (Bill::NOMINAL_TAGIHAN as $kode => $nominal) {
                    $refSheet->setCellValue("A{$row}", $kode);
                    $refSheet->setCellValue("B{$row}", (int) $nominal);
                    $row++;
                }

                // Sembunyikan sheet "Ref"
                $refSheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);

                // ============================================
                // 3. FORMULA OTOMATIS UNTUK NOMINAL (KOLOM G)
                // ============================================
                // Tulis formula ke G2 sampai G1000
                for ($i = 2; $i <= 1000; $i++) {
                    $sheet->setCellValue(
                        "G{$i}",
                        "=IFERROR(VLOOKUP(D{$i},Ref!\$A\$2:\$B\$" . ($row - 1) . ",2,FALSE),\"\")"
                    );
                }
            }
        ];
    }

}
