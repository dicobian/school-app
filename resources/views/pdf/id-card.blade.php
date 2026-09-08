<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ID Card Siswa</title>
    <style>
        @page {
            margin: 15px;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
        }

        .card-table {
            width: 100%;
            border-collapse: collapse;
        }

        .card-table td {
            width: 50%;
            padding: 6px;
            vertical-align: top;
        }

        .card {
            width: 100%;
            max-width: 300px;
            height: 180px;
            border: 1.5px solid #1a1a1a;
            border-radius: 12px;
            padding: 12px;
            display: flex;
            align-items: center;
            box-sizing: border-box;
            page-break-inside: avoid;
            margin: 0 auto; /* Tengah jika ada space */
        }

        .card .qr {
            width: 90px;
            height: 90px;
            flex-shrink: 0;
        }

        .card .qr img {
            width: 100%;
            height: 100%;
            display: block;
        }

        .card .info {
            padding-left: 14px;
            flex: 1;
            min-width: 0;
        }

        .card .info .school {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 4px 0;
        }

        .card .info .nama {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 4px 0;
            word-wrap: break-word;
        }

        .card .info .kelas {
            font-size: 11px;
            color: #333;
            margin: 0 0 2px 0;
        }

        .card .info .nisn {
            font-size: 9px;
            color: #888;
            margin: 0;
        }

        /* Untuk cetak */
        @media print {
            .card-table td {
                padding: 4px;
            }

            .card {
                height: 170px;
                padding: 10px;
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .card .qr {
                width: 80px;
                height: 80px;
            }
        }

        /* Untuk layar kecil */
        @media (max-width: 600px) {
            .card-table td {
                display: block;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <table class="card-table">
        @php
            $chunked = $students->chunk(2);
        @endphp

        @foreach ($chunked as $row)
            <tr>
                @foreach ($row as $student)
                    <td>
                        <div class="card">
                            <div class="qr info">
                                <img src="{{ $student->qr_data_uri }}" alt="QR">
                            </div>
                            <div class="info">
                                <p class="school">MI Azzahra Caringin</p>
                                <p class="nama">{{ $student->nama }}</p>
                                <p class="kelas">{{ $student->classroom->name ?? '-' }}</p>
                                <p class="nisn">NISN: {{ $student->nisn ?: '-' }}</p>
                            </div>
                        </div>
                    </td>
                @endforeach

                @if ($row->count() == 1)
                    <td></td> <!-- Kosongkan jika jumlah ganjil -->
                @endif
            </tr>
        @endforeach
    </table>
</body>
</html>
