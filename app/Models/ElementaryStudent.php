<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElementaryStudent extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'student_id');
    }

    protected static function booted(): void
    {
        static::creating(function (ElementaryStudent $student) {
            if (empty($student->barcode)) {
                $student->barcode = 'SISWA-' . strtoupper(uniqid());
            }
        });
    }
}
