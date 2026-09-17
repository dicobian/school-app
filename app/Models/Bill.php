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
        'infaq_bulanan_spp'          => '150000',
        'buku'                       => '250000',
        'daftar_ulang'               => '500000',
        'administrasi_kelas_6'       => '300000',
        'foto'                       => '50000',
        'rihlah'                     => '200000',
        'akhiru_sanah'               => '200000',
        'pendaftaran_murid_baru'     => '350000',
        'administrasi_murid_baru'    => '150000',
    ];
}
