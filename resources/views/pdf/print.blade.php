<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pemberitahuan Tagihan - {{ $student->nama }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
        }

        body {
            font-family: 'Times-Roman', 'Times New Roman', serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* ===== KOP SURAT ===== */
        .kop-surat {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #000;
            margin-bottom: 15px;
        }

        .kop-surat td {
            vertical-align: middle;
            padding-bottom: 8px;
        }

        .kop-logo {
            width: 90px;
            text-align: center;
        }

        .kop-logo img {
            width: 80px;
            height: auto;
        }

        .kop-teks {
            text-align: center;
            padding-right: 90px; /* kompensasi lebar kolom logo supaya teks center */
        }

        .kop-teks h2 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
        }

        .kop-teks h3 {
            margin: 2px 0 0 0;
            font-size: 12pt;
            font-weight: normal;
        }

        .kop-teks p {
            margin: 2px 0 0 0;
            font-size: 9pt;
            font-style: italic;
        }

        /* ===== HEADER SURAT ===== */
        .header-surat {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        .header-surat td {
            vertical-align: top;
            font-size: 11pt;
        }

        .tujuan-surat {
            margin-bottom: 15px;
        }

        /* ===== TABEL TAGIHAN ===== */
        .table-tagihan {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
        }

        .table-tagihan th, .table-tagihan td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 10.5pt;
        }

        .table-tagihan th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; }

        /* ===== INFO PEMBAYARAN ===== */
        .info-pembayaran {
            margin-top: 10px;
            background-color: #f9f9f9;
            border-left: 3px solid #333;
            padding: 8px 12px;
            font-size: 10pt;
        }

        /* ===== TANDA TANGAN ===== */
        .ttd-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        .ttd-table td {
            text-align: center;
            vertical-align: top;
        }
    </style>
</head>
<body>

    <!-- ===== KOP SURAT ===== -->
    <table class="kop-surat">
        <tr>
            <td class="kop-logo">
                <img src="{{ public_path('assets/azzahra-logo.png') }}" alt="Logo">
            </td>
            <td class="kop-teks">
                <h2>{{ $yayasan }}</h2>
                <h3>{{ $sd }}</h3>
                <p>{{ $alamat }}</p>
                <p>Telepon: {{ $nomor }} | Email: {{ $email }}</p>
            </td>
        </tr>
    </table>

    <!-- ===== NOMOR & HAL ===== -->
    {{-- <table class="header-surat">
        <tr>
            <td width="15%">Nomor</td>
            <td width="2%">:</td>
            <td width="48%">045/SPP/SDIT/{{ date('m/Y') }}</td>
            <td width="35%" class="text-right">Pandeglang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>-</td>
            <td></td>
        </tr>
        <tr>
            <td>Hal</td>
            <td>:</td>
            <td><strong>Pemberitahuan Tagihan Pembayaran</strong></td>
            <td></td>
        </tr>
    </table> --}}

    <!-- ===== TUJUAN SURAT ===== -->
    <div class="tujuan-surat">
        Kepada Yth.<br>
        Bapak/Ibu Orang Tua / Wali dari:<br>
        <strong>Nama Siswa:</strong> {{ $student->nama }}<br>
        <strong>Kelas:</strong> {{ ucwords($student->tingkat_rombel) }}
    </div>

    <!-- ===== ISI SURAT ===== -->
    <p style="margin: 5px 0;">Assalamu’alaikum Warahmatullahi Wabarakatuh,</p>

    <p style="margin: 5px 0; text-align: justify;">
        Semoga Bapak/Ibu beserta keluarga senantiasa berada dalam keadaan sehat walafiat serta sukses dalam menjalankan aktivitas sehari-hari.
    </p>

    <p style="margin: 5px 0; text-align: justify;">
        Sehubungan dengan pelaksanaan kegiatan belajar mengajar serta kelancaran operasional sekolah, bersama surat ini kami sampaikan rincian kewajiban administrasi/tagihan biaya pendidikan yang belum terselesaikan dengan rincian sebagai berikut:
    </p>

    <!-- ===== TABEL RINCIAN TAGIHAN ===== -->
    <table class="table-tagihan">
        <thead>
            <tr>
                <th width="8%">No</th>
                <th>Rincian Tagihan</th>
                <th>Tahun Ajaran</th>
                <th>Bulan</th>
                <th width="28%">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bills as $index => $bill)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $bill->nama_tagihan)) }}</td>
                    <td>{{ ucwords($bill->tahun_ajaran) }}</td>
                    <td>{{ ucwords($bill->bulan) }}</td>
                    <td class="text-right">{{ number_format($bill->nominal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada tagihan yang harus dibayar.</td>
                </tr>
            @endforelse

            <tr class="total-row">
                <td colspan="4" class="text-right">Total Tagihan</td>
                <td class="text-right">Rp {{ number_format($totalNominal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- ===== INSTRUKSI PEMBAYARAN ===== -->
    <div class="info-pembayaran">
        <strong>Ketentuan Pembayaran:</strong>
        <ol style="margin: 3px 0; padding-left: 18px;">
            {{-- <li>Pembayaran dapat dilakukan paling lambat pada tanggal <strong>15 {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</strong>.</li> --}}
            <li>Pembayaran bisa dilakukan via Transfer Bank <strong>{{ $rekening }}</strong> a.n. <strong>{{ $namarekening }}</strong>.</li>
            <li>Konfirmasi bukti transfer ke Keuangan via WA: <strong>{{ $whatsapp }}</strong>.</li>
        </ol>
    </div>

    <p style="margin: 8px 0;">Demikian surat pemberitahuan ini kami sampaikan. Atas perhatian dan kerja samanya, kami ucapkan terima kasih.</p>

    <p style="margin: 5px 0;">Wassalamu’alaikum Warahmatullahi Wabarakatuh.</p>

    <!-- ===== TANDA TANGAN ===== -->
    <table class="ttd-table">
        <tr>
            <td width="60%"></td>
            <td width="40%">
                <p style="margin: 0;">Caringin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p style="margin: 0;">Kepala Mi Azzahra Caringin</p>
                <img style="width: 200px" src="{{ public_path('assets/cap-tandatangan.png') }}" alt="Logo">
                <p style="margin: 0;"><strong>( Ratu Romdhonah S.Ag., M.Pd. )</strong></p>
            </td>
        </tr>
    </table>

</body>
</html>
