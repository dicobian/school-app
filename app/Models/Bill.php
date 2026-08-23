<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bill extends Model
{
    protected $fillable = [
        'student_id',
        'nama',
        'deskripsi',
        'bulan_tahun',
        'nominal',
        'status',
        'tanggal_bayar',
    ];

    public function students(): BelongsTo
    {
        return $this->belongsTo(ElementaryStudent::class, 'student_id');
    }
}
