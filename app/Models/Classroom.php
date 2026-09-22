<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'deskripsi'
    ];

     public function students(): HasMany
    {
        return $this->hasmany(ElementaryStudent::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function nextClassroom(): ?self
    {
        if(!$this->tingkat) return null;

        return static::where('tingkat', $this->tingkat + 1)->first();
    }
}
