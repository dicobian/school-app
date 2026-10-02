<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiPembayaran extends Model
{
    protected $fillable =
    [
        'student_id',
        'nama_pembayaran',
        'kategori_pembayaran',
        'keterangan',
        'tanggal_bayar',
        'nominal'
    ];

    public function students(): BelongsTo
    {
        return $this->belongsTo(ElementaryStudent::class, 'student_id');
    }

    public const JENIS_PEMBAYARAN = [
        'infaq_bulanan_spp'          => 'infaq_bulanan_spp',//1
        'administrasi_murid_baru'    => 'administrasi_murid_baru',//2
        'administrasi_daftar_ulang'  => 'administrasi_daftar_ulang',//3
        'administrasi_kelas_6'       => 'administrasi_kelas_6',//4
        'buku_paket_1'               => 'buku_paket_1',//5
        'buku_paket_2'               => 'buku_paket_2',//6
        'foto_ijazah'                => 'foto_ijazah',//7
        'akhiru_sanah'               => 'akhiru_sanah',//8
        'pendaftaran_murid_baru'     => 'pendaftaran_murid_baru',//9
        'tunggakan_lain'             => 'tunggakan_lain',//9
    ];
}
