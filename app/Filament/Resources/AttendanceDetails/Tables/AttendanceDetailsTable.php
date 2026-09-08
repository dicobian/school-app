<?php

namespace App\Filament\Resources\AttendanceDetails\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;


class AttendanceDetailsTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('attendance_id')
                ->label('id')
                ->sortable(),
            TextColumn::make('attendance.classroom.name')
                ->label('Kelas')
                ->sortable()
                ->searchable(),
            TextColumn::make('student_id')
                ->label('student_id'),
            TextColumn::make('student.nama')
                ->label('nama siswa'),
            TextColumn::make('status')
                ->label('Status Kehadiran')
                ->badge()
                // 1. Mengubah teks English di DB menjadi Bahasa Indonesia saat tampil
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'present' => 'Hadir',
                    'late'    => 'Terlambat',
                    'excused' => 'Izin / Sakit',
                    'absent'  => 'Tidak Hadir',
                    default   => $state,
                })
                // 2. Memberi warna badge berdasarkan nilai database
                ->color(fn (string $state): string => match ($state) {
                    'present' => 'success', // Hijau
                    'late'    => 'warning', // Kuning/Oranye
                    'excused' => 'info',    // Biru
                    'absent'  => 'danger',  // Merah
                    default   => 'gray',    // Abu-abu
                }),
            TextColumn::make('attendance.date')
                ->label('tanggal')
                ->date('d M Y (l)')
        ])
        ->filters([
            // 2. Filter Berdasarkan Kelas (Menggunakan relationship dot notation)
            SelectFilter::make('classroom')
                ->label('Filter Kelas')
                ->relationship('attendance.classroom', 'name'),

            // 3. Filter Berdasarkan Tanggal Absensi
            Filter::make('attendance_date')
                ->form([
                    DatePicker::make('date')
                        ->label('Pilih Tanggal')
                        ->default(now()), // Default ke hari ini
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        $data['date'],
                        fn (Builder $q, $date) => $q->whereHas(
                            'attendance',
                            fn (Builder $attendanceQuery) => $attendanceQuery->whereDate('date', $date)
                        )
                    );
                }),
        ]);
    }
}
