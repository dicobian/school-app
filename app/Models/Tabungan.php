<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tabungan extends Model
{
    protected $fillable = [
        'student_id',
        'jenis_transaksi_tabungan',
        'nominal',
        'keterangan'
    ];

    public function students(): BelongsTo
    {
        return $this->belongsTo(ElementaryStudent::class, 'student_id');
    }

}
