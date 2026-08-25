<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceDetail extends Model
{
    protected $fillable = [
        'attendance_id',
        'student_id',
        'status',
        'description'
    ];
   public function student(): BelongsTo
    {
        return $this->belongsTo(ElementaryStudent::class); // Sesuaikan dengan nama model Student
    }

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }
}
