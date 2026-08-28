<?php

namespace App\Filament\Resources\AttendanceDetails;

use App\Filament\Resources\AttendanceDetails\Pages\CreateAttendanceDetail;
use App\Filament\Resources\AttendanceDetails\Pages\EditAttendanceDetail;
use App\Filament\Resources\AttendanceDetails\Pages\ListAttendanceDetails;
use App\Filament\Resources\AttendanceDetails\Pages\ViewAttendanceDetail;
use App\Filament\Resources\AttendanceDetails\Schemas\AttendanceDetailForm;
use App\Filament\Resources\AttendanceDetails\Schemas\AttendanceDetailInfolist;
use App\Filament\Resources\AttendanceDetails\Tables\AttendanceDetailsTable;
use App\Models\AttendanceDetail;
use BackedEnum;
use Dom\Text;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class AttendanceDetailResource extends Resource
{
    protected static ?string $model = AttendanceDetail::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // protected static ?string $recordTitleAttribute = 'attendance_detail';

    protected static string | UnitEnum | null $navigationGroup = 'Absensi';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return AttendanceDetailForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AttendanceDetailInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        // return AttendanceDetailsTable::configure($table);
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendanceDetails::route('/'),
            'create' => CreateAttendanceDetail::route('/create'),
            'view' => ViewAttendanceDetail::route('/{record}'),
            'edit' => EditAttendanceDetail::route('/{record}/edit'),
        ];
    }
}
