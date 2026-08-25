<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Tables;
use App\Models\Classroom;
use App\Models\ElementaryStudent;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use UnitEnum;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;

use Filament\Support\Icons\Heroicon;

class RekapAbsensi extends Page implements Forms\Contracts\HasForms, Tables\Contracts\HasTable
{
    use Forms\Concerns\InteractsWithForms;
    use Tables\Concerns\InteractsWithTable;

    // protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentChartBar;
    // protected static ?string $navigationGroup = 'Absensi';
    protected static string | UnitEnum | null $navigationGroup = 'Absensi';
    protected static ?string $title = 'Rekap Kehadiran Siswa';
    protected string $view = 'filament.pages.rekap-absensi';

    // State penampung filter, variable ini nyambung sama yang ada di ->schema([]) binding automatis kayak di react
    public ?string $classroom_id = null;
    public ?string $academic_year = '2025/2026';
    public ?string $semester = '1';

    public function mount(): void
    {
        // Set nilai default awal
        $year = now()->year;
        $month = now()->month;

        // Jika bulan Juli ke atas, masuk T.A tahun ini / tahun depan
        // Jika di bawah Juli, masuk T.A tahun lalu / tahun ini
        $defaultAcademicYear = $month >= 7
            ? "{$year}/" . ($year + 1)
            : ($year - 1) . "/{$year}";

        $this->form->fill([
        'academic_year' => $defaultAcademicYear,
        'semester' => '1',
        ]);
    }

    // 1. Form Filter (Kelas, Tahun Ajaran, Semester)
    protected function getFormSchema(): array
    {
        return [
            Section::make('Filter Rekapitulasi')
                ->schema([
                    Select::make('classroom_id')
                        ->label('Pilih Kelas')
                        ->options(Classroom::pluck('name', 'id')) // pluck hanya mengambil name dan id dari classroom
                        ->required()
                        ->live(),
                     // Select::make('academic_year')
                    //         ->label('Tahun Ajaran')
                    //         ->options([
                    //             '2024/2025' => '2024/2025',
                    //             '2025/2026' => '2025/2026',
                    //             '2026/2027' => '2026/2027',
                    //         ])
                    //         ->required()
                    //         ->live(),

                    Select::make('academic_year')
                        ->label('Tahun Ajaran')
                        ->options(function () {
                            $currentYear = now()->year;
                            $options = [];

                            // Generates range, misal: dari 2 tahun lalu sampai 2 tahun ke depan
                            for ($i = -2; $i <= 2; $i++) {
                                $start = $currentYear + $i;
                                $end = $start + 1;
                                $yearString = "{$start}/{$end}";
                                $options[$yearString] = $yearString;
                            }

                            return $options;
                        })
                        ->required()
                        ->live(),

                    Select::make('semester')
                        ->label('Semester')
                        ->options([
                            '1' => 'Semester 1 (Ganjil)',
                            '2' => 'Semester 2 (Genap)',
                        ])
                        ->required()
                        ->live(),
                ])->columns(3),
        ];
    }

    // 2. Query Data Siswa
    protected function getTableQuery(): Builder
    {
        // Jika belum memilih kelas, kembalikan kueri kosong (tabel kosong)
        if (! $this->classroom_id) {
            return ElementaryStudent::query()->whereRaw('1 = 0');
        }

        return ElementaryStudent::query()->where('classroom_id', $this->classroom_id);
    }

    // 3. Kolom Kalkulasi Persentase
    protected function getTableColumns(): array // scara automatis menggunakan data yang dikembalian dari gettablequery
    {
        $totalPertemuan = $this->getTotalPertemuan();

        return [
            Tables\Columns\TextColumn::make('nisn')
                ->label('NISN'),

            Tables\Columns\TextColumn::make('nama')
                ->label('Nama Siswa')
                ->searchable(),

            Tables\Columns\TextColumn::make('total_present')
                ->label('Hadir')
                ->alignCenter()
                ->getStateUsing(fn ($record) => $this->countStatus($record->id, 'present')), // record disini apa yang dikembalikan dari get table query, dan isinya data siswa

            Tables\Columns\TextColumn::make('total_late')
                ->label('Terlambat')
                ->alignCenter()
                ->getStateUsing(fn ($record) => $this->countStatus($record->id, 'late')), // getstate using gunanya mengkalkulais hasil 

            Tables\Columns\TextColumn::make('total_excused')
                ->label('Izin/Sakit')
                ->alignCenter()
                ->getStateUsing(fn ($record) => $this->countStatus($record->id, 'excused')),

            Tables\Columns\TextColumn::make('total_absent')
                ->label('Alpha')
                ->alignCenter()
                ->getStateUsing(fn ($record) => $this->countStatus($record->id, 'absent')),

            Tables\Columns\TextColumn::make('percentage')
                ->label('Persentase Kehadiran')
                ->alignCenter()
                ->badge()
                ->color(fn ($state) => (float) str_replace('%', '', $state) >= 85 ? 'success' : ((float) str_replace('%', '', $state) >= 75 ? 'warning' : 'danger'))
                ->getStateUsing(function ($record) {
                    // 1. Ambil total pertemuan/sesi absensi kelas
                    $totalPertemuan = $this->getTotalPertemuan();

                    if ($totalPertemuan === 0) {
                        return '0%';
                    }

                    // 2. Hitung jumlah 'present' khusus untuk siswa ($record->id) ini
                    $hadir = $this->countStatus($record->id, 'present');

                    // 3. Pastikan dikonversi ke float agar hasil bagi tidak menjadi 0
                    $percentage = round(((float) $hadir / (float) $totalPertemuan) * 100, 1);

                    return $percentage . '%';
                }),
        ];
    }

    // --- HELPER LOGIC ---

    // Mengambil daftar ID absensi kelas pada semester & tahun ajaran tertentu
    private function getAttendanceIds(): array
    {
        if (! $this->classroom_id || ! $this->academic_year || ! $this->semester) {
            return [];
        }

        [$startYear, $endYear] = explode('/', $this->academic_year);

        // Menentukan rentang tanggal semester
        if ($this->semester === '1') {
            $startDate = "{$startYear}-07-01";
            $endDate = "{$startYear}-12-31";
        } else {
            $startDate = "{$endYear}-01-01";
            $endDate = "{$endYear}-06-30";
        }

        return Attendance::where('classroom_id', $this->classroom_id)
            ->whereBetween('date', [$startDate, $endDate])
            ->pluck('id')
            ->toArray();
    }

    private function getTotalPertemuan(): int
    {
        return count($this->getAttendanceIds());
    }

    private function countStatus(int $studentId, string $status): int
    {
        $attendanceIds = $this->getAttendanceIds();

        if (empty($attendanceIds)) return 0;

        return AttendanceDetail::whereIn('attendance_id', $attendanceIds)
            ->where('student_id', $studentId)
            ->where('status', $status)
            ->count();
    }
}
