<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\ElementaryStudent;
use Barryvdh\DomPDF\Facade\Pdf;

class BillsController extends Controller
{
    public function printPdf(ElementaryStudent $student){
        $student->load(['bills' => function($query){
            $query->where('status', '!=', 'lunas');
        }]);
        $bills = $student->bills;
        $totalNominal = $bills->sum('nominal');
        $yayasan = 'YAYASAN PENDIDIKAN TUBAGUS RASYAD';
        $sd = 'MI AZZAHRA CARINGIN';
        $alamat = 'Caringin Mesjid RT 11 RW 03 No, Caringin, Kec. Labuan, Kabupaten Pandeglang, Banten 42264';
        $nomor = '087772702008';
        $email = 'miazzahra@gmail.com';
        $rekening = 'BSI: 7123456789';
        $namarekening = 'MI AZZAHRA';
        $whatsapp = '087772702008';


        $pdf = Pdf::loadView('pdf.print', compact('student', 'bills', 'totalNominal', 'yayasan', 'sd', 'alamat', 'nomor', 'email', 'rekening', 'namarekening', 'whatsapp'))
            ->setPaper('a4', 'portrait');

        $namaFile = 'Tagihan_' . str_replace(' ', '_', $student->nama) . '.pdf';

        // Opsi A: Langsung download otomatis di browser
        return $pdf->download($namaFile);

        // Opsi B: Buka/Pratinjau di tab browser (rekomendasi)
        // return $pdf->stream($namaFile);
    }
}
