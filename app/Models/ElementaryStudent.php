<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

use function PHPUnit\Framework\returnArgument;

class ElementaryStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'classroom_id',
        'nama',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'tingkat_rombel',
        'umur',
        'status',
        'jenis_kelamin',
        'alamat',
        'nomor_telepon',
        'kebutuhan_khusus',
        'disabilitas',
        'nomor_kip_pip',
        'nama_ayah',
        'nama_ibu',
        'nama_wali',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'student_id');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(TransaksiPembayaran::class, 'student_id');
    }

    protected static function booted(): void
    {
        static::creating(function (ElementaryStudent $student) {
            if (empty($student->barcode)) {
                $student->barcode = 'SISWA-' . strtoupper(uniqid());
            }
        });
    }



    //method logika

    public function scopePunyaTunggakan(Builder $query):Builder
    {
        return $query->whereRaw('
                (SELECT COALESCE(SUM(nominal), 0) FROM bills WHERE bills.student_id = elementary_students.id)
                -
                (SELECT COALESCE(SUM(nominal), 0) FROM transaksi_pembayarans WHERE transaksi_pembayarans.student_id = elementary_students.id)
                > 0
        ');
    }

    public function totalTagihan(): int|float
    {
        return $this->bills()->sum('nominal');
    }

    public function totalPembayaran(): int|float
    {
        return $this->pembayaran()->sum('nominal');
    }

    public function getSisaTagihanAttribute(): int|float//penambahan kata 'Attribute' disini sebagai accessor, supaya nanti bisa dipanggil $student->sisa_tagihan, menjadikan method seakan akan atribute, kita akan sering akses method ini sebagai atribut
    {//karenan nominal sisa tagihan tidak disimpan dalam database, kita biarkan perhitungan logika bisnis yang menghasilkannya
        //RUMUS PENAMAAN ATTRIBUTE  =>  get + NamaProperty + Attribute
        //PANGGIL ACCESSOR TANPA TANDA KURUNG  $student->sisa_tagihan , kalau tidak nanti ERROR
        //max() disini untuk mencegah nilai minus, cara kerja nya max itu akan memilih nilai terbesar
        //dengan ada nya angka 0 tiap kali ada angka minus dari perhitungan ini maka automatis 0 akan dipilih
        return max(0, $this->totalTagihan() - $this->totalPembayaran());
    }
    public function getSisaSaldoAttribute(): int|float
    {
        return max(0, $this->totalPembayaran() - $this->totalTagihan());
    }

    public function getSisaTunggakanSppAttribute(): int|float
    {
        $tunggakan = $this->bills()->where('nama_tagihan', 'infaq_bulanan_spp')->sum('nominal');
        $pembayaran = $this->pembayaran()->where('kategori_pembayaran', 'infaq_bulanan_spp')->sum('nominal');
        return max(0, $tunggakan - $pembayaran) ;
    }
    public function getSisaTunggakanAdmrMuridBaruAttribute(): int|float
    {
        $tunggakan = $this->bills()->where('nama_tagihan', 'administrasi_murid_baru')->sum('nominal');
        $pembayaran = $this->pembayaran()->where('kategori_pembayaran', 'administrasi_murid_baru')->sum('nominal');
        return max(0, $tunggakan - $pembayaran) ;
    }
    public function getSisaTunggakanAdmrDaftarUlangAttribute(): int|float
    {
        $tunggakan = $this->bills()->where('nama_tagihan', 'administrasi_daftar_ulang')->sum('nominal');
        $pembayaran = $this->pembayaran()->where('kategori_pembayaran', 'administrasi_daftar_ulang')->sum('nominal');
        return max(0, $tunggakan - $pembayaran) ;
    }
    public function getSisaTunggakanAdmrKelas6Attribute(): int|float
    {
        $tunggakan = $this->bills()->where('nama_tagihan', 'administrasi_kelas_6')->sum('nominal');
        $pembayaran = $this->pembayaran()->where('kategori_pembayaran', 'administrasi_kelas_6')->sum('nominal');
        return max(0, $tunggakan - $pembayaran) ;
    }
    public function getSisaTunggakanBukuPaket1Attribute(): int|float
    {
        $tunggakan = $this->bills()->where('nama_tagihan', 'buku_paket_1')->sum('nominal');
        $pembayaran = $this->pembayaran()->where('kategori_pembayaran', 'buku_paket_1')->sum('nominal');
        return max(0, $tunggakan - $pembayaran) ;
    }
    public function getSisaTunggakanBukuPaket2Attribute(): int|float
    {
        $tunggakan = $this->bills()->where('nama_tagihan', 'buku_paket_2')->sum('nominal');
        $pembayaran = $this->pembayaran()->where('kategori_pembayaran', 'buku_paket_2')->sum('nominal');
        return max(0, $tunggakan - $pembayaran) ;
    }
    public function getSisaTunggakanFotoIjazahAttribute(): int|float
    {
        $tunggakan = $this->bills()->where('nama_tagihan', 'foto_ijazah')->sum('nominal');
        $pembayaran = $this->pembayaran()->where('kategori_pembayaran', 'foto_ijazah')->sum('nominal');
        return max(0, $tunggakan - $pembayaran) ;
    }
    public function getSisaTunggakanAkhiruSanahAttribute(): int|float
    {
        $tunggakan = $this->bills()->where('nama_tagihan', 'akhiru_sanah')->sum('nominal');
        $pembayaran = $this->pembayaran()->where('kategori_pembayaran', 'akhiru_sanah')->sum('nominal');
        return max(0, $tunggakan - $pembayaran) ;
    }
    public function getSisaTunggakanLainAttribute(): int|float
    {
        $tunggakan = $this->bills()->where('nama_tagihan', 'tunggakan_lain')->sum('nominal');
        $pembayaran = $this->pembayaran()->where('kategori_pembayaran', 'tunggakan_lain')->sum('nominal');
        return max(0, $tunggakan - $pembayaran) ;
    }
}
