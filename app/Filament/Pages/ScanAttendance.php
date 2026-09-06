<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\ElementaryStudent;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class ScanAttendance extends Page
{
    protected string $view = 'filament.pages.scan-attendance';
    // protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::QrCode;
    protected static ?string $navigationLabel = 'Scan Absensi';
    protected static ?string $title = 'Scan Absensi Siswa';
    protected static ?string $slug = 'scan-absensi';

    public ?ElementaryStudent $scannedStudent = null;
    public ?string $message = null;
    public bool $alreadyMarked = false;

    public function lookupBarcode(string $code): void {
        $this->message = null;
        $this->alreadyMarked = false;

        $student = ElementaryStudent::with('classroom')
            ->where('barcode', $code)
            ->first();

        if (! $student){
            $this->scannedStudent = null;
            $this->message = 'Barcode Tidak dikenali';
            return;
        }

        $this->scannedStudent = $student;

        $attendance = Attendance::where('classroom_id', $student->classroom_id)
            ->whereDate('date', now()->toDateString())
            ->first();

        if($attendance) {
            $this->alreadyMarked = AttendanceDetail::where('attendance_id', $attendance->id)
                ->where('student_id', $student->id)
                ->exists();
        }
    }

    public function markPresent(): void
    {
        if (! $this->scannedStudent) {
            return;
        }
        $attendance = Attendance::firstOrCreate([
            'classroom_id' => $this->scannedStudent->classroom_id,
            'date' => now()->toDateString()
        ]);

        AttendanceDetail::updateOrCreate(
            [
                'attendance_id' => $attendance->id,
                'student_id' => $this->scannedStudent->id,
            ],
            [
                'status' => 'hadir'
            ]
        );

        $this->alreadyMarked = true;
        $this->message = 'Absensi berhasil dicatat untuk ' . $this->scannedStudent->nama;
    }

    public function resetScan(): void
    {
        $this->scannedStudent = null;
        $this->message = null;
        $this->alreadyMarked = false;
    }
}
