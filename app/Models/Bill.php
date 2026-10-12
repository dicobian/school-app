<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bill extends Model
{
    protected $fillable = [
        'student_id',
        'nama_tagihan',
        'tahun_ajaran',
        'keterangan',
        'bulan',
        'nominal',
        'status',
        'tanggal_bayar',
    ];


    public function students(): BelongsTo
    {
        return $this->belongsTo(ElementaryStudent::class, 'student_id');
    }

    public const NOMINAL_TAGIHAN = [ //ini bukan variable konstanta class dan variable berbeda disini
        'infaq_bulanan_spp'          => '110000',//1
        'administrasi_murid_baru'    => '250000',//2
        'administrasi_daftar_ulang'  => '500000',//3
        'administrasi_kelas_6'       => '300000',//4
        'buku_paket_1'               => '200000',//5
        'buku_paket_2'               => '200000',//6
        'foto_ijazah'                => '50000',//7
        'akhiru_sanah'               => '50000',//8
        'pendaftaran_murid_baru'     => '350000',//9
    ];

    public const BULAN = [
        'januari' => 'Januari',
        'februari' => 'Februari',
        'maret' => 'Maret',
        'april' => 'April',
        'mei' => 'Mei',
        'juni' => 'Juni',
        'juli' => 'Juli',
        'agustus' => 'Agustus',
        'september' => 'September',
        'oktober' => 'Oktober',
        'november' => 'November',
        'desember' => 'Desember',
    ];
}
