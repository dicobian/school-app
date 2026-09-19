<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\ElementaryStudent;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use ZipArchive;

class BillsController extends Controller
{
    //print satu persatu
    public function printPdf(ElementaryStudent $student)
    {
        $pdf = $this->generateBillPdf($student);
        $namaFile = 'Tagihan_' . str_replace(' ', '_', $student->nama) . $student->tingkat_rombel . '.pdf';
        // return $pdf;
        return $pdf->stream($namaFile);
        return $pdf->download($namaFile);
    }

    public function printBulkPdf(Collection $students)
    {
        $folderName = 'temp/tagihan-' . uniqid();
        $tempPath = storage_path('app/' . $folderName);

        if(! is_dir($tempPath)) {
            mkdir($tempPath, 0777, true);
        }

        foreach ($students as $student) {
            $pdf = $this->generateBillPdf($student);
            $saveName = Str::slug($student->tingkat_rombel . '-' . $student->nama);
            $pdf->save($tempPath . '/' . $saveName . '.pdf');
        }

        $zipFileName = 'tagihan-siswa-' . now()->format('Y-m-d_His') . '.zip';
        $zipPath = storage_path('app/' . $zipFileName);
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach(glob($tempPath . '/*.pdf') as $file) {
            $zip->addFile($file, basename($file));
        }
        $zip->close();

        array_map('unlink', glob($tempPath . '/*.pdf'));
        rmdir($tempPath);

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    //fungsi utama untuk generate pdf
    public function generateBillPdf(ElementaryStudent $student){
        $student->load(['bills' => function($query){
            $query->where('status', '!=', 'lunas');
        }]);
        $bills = $student->bills;
        $totalNominal = $bills->sum('nominal');
        $yayasan = 'YAYASAN KIAYI HAJI AHMAD RASYAD AL ASNAWI';
        $sd = 'MADRASAH IBTIDAIYAH AZZAHRA CARINGIN';
        $alamat = 'Caringin Mesjid RT 11 RW 03 No, Caringin, Kec. Labuan, Kabupaten Pandeglang, Banten 42264';
        $nomor = '087772702008';
        $email = 'miazzahra@gmail.com';
        $rekening = 'BSI: 7123456789';
        $namarekening = 'MI AZZAHRA';
        $whatsapp = '087772702008';

        // return view('pdf.print', compact('student', 'bills', 'totalNominal', 'yayasan', 'sd', 'alamat', 'nomor', 'email', 'rekening', 'namarekening', 'whatsapp'));
        return Pdf::loadView('pdf.print', compact('student', 'bills', 'totalNominal', 'yayasan', 'sd', 'alamat', 'nomor', 'email', 'rekening', 'namarekening', 'whatsapp'))
                    ->setPaper('a4', 'portrait');


        // return $pdf = Pdf::loadView('pdf.print', compact('student', 'bills', 'totalNominal', 'yayasan', 'sd', 'alamat', 'nomor', 'email', 'rekening', 'namarekening', 'whatsapp'))
        //             ->setPaper('a4', 'portrait');

        // $namaFile = 'Tagihan_' . str_replace(' ', '_', $student->nama) . '.pdf';

        // // Opsi A: Langsung download otomatis di browser
        // return $pdf->download($namaFile);

        // // Opsi B: Buka/Pratinjau di tab browser (rekomendasi)
        // // return $pdf->stream($namaFile);
    }
}
