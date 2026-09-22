<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    protected $fillable = [
        'name',
        'status',
        'tanggal_mulai',
        'tanggal_selesai'
    ];

    protected $casts = [
        'tanggal_mulai'     => 'date',
        'tanggal_selesai'   => 'date'
    ];

    public function students(): HasMany
    {
        return $this->hasMany(ElementaryStudent::class);
    }

    public static function active(): ?self
    {
        return static::where('status', 'aktif')->first();
    }

    public static function next(): ?self
    {
        return static::where('status', 'akan_datang')
            ->orderBy('tanggal_mulai')
            ->first();
    }

    public function activate(): void
    {
       DB::transaction(function () {
            static::where('id', '!=', $this->id)
                ->where('status', 'aktif')
                ->update(['status' => 'arsip']);

                $this->update(['status' => 'aktif']);
       });
    }
}
