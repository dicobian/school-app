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
        }
        .card-grid {
            display: flex;
            flex-wrap: wrap;
        }
        .card {
            width: 300px;
            height: 180px;
            border: 1.5px solid #1a1a1a;
            border-radius: 12px;
            margin: 6px;
            padding: 12px;
            display: flex;
            align-items: center;
            box-sizing: border-box;
            page-break-inside: avoid;
        }
        .card .qr {
            width: 90px;
            height: 90px;
            flex-shrink: 0;
        }
        .card .qr img {
            width: 100%;
            height: 100%;
        }
        .card .info {
            padding-left: 14px;
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
    </style>
</head>
<body>
    <div class="card-grid">
        @foreach ($students as $student)
            <div class="card">
                <div class="qr">
                    <img src="{{ $student->qr_data_uri }}" alt="QR">
                </div>
                <div class="info">
                    <p class="school">MI Azzahra Caringin</p>
                    <p class="nama">{{ $student->nama }}</p>
                    <p class="kelas">{{ $student->classroom->name ?? '-' }}</p>
                    <p class="nisn">NISN: {{ $student->nisn ?: '-' }}</p>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
