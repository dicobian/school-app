<?php

namespace App\Filament\Resources\Attendances\Schemas;

use App\Models\ElementaryStudent;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Absensi')
                    ->schema([
                        Select::make('classroom_id')
                            ->label('Kelas')
                            ->relationship('classroom', 'name')
                            ->required()
                            ->live(),

                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal Absensi')
                            ->default(now())
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (
                                Set $set,
                                $state
                            ) {
                                if ($state) {
                                    $dayName = Carbon::parse($state)
                                        ->translatedFormat('l');

                                    $set('day_display', $dayName);
                                }
                            }),

                        Forms\Components\TextInput::make('day_display')
                            ->label('Hari')
                            ->readOnly()
                            ->default(
                                now()->translatedFormat('l')
                            ),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Opsional')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('Daftar Kehadiran Siswa')
                    ->schema([
                        Forms\Components\Repeater::make('details')
                            ->relationship('details')
                            ->schema([
                                Select::make('student_id')
                                ->label('Siswa')
                                ->options(function (Get $get) {

                                    $classroomId = $get('../../classroom_id');

                                    if (! $classroomId) {
                                        return [];
                                    }

                                    // Siswa yang sedang dipilih oleh item repeater ini
                                    $currentStudent = $get('student_id');

                                    // Semua siswa yang sudah dipilih pada repeater
                                    $selectedStudents = collect(
                                        $get('../../details') ?? []
                                    )
                                        ->pluck('student_id')
                                        ->filter()
                                        // Jangan exclude siswa pada item yang sedang aktif
                                        ->reject(fn ($id) => $id == $currentStudent)
                                        ->values()
                                        ->toArray();

                                    return ElementaryStudent::query()
                                        ->where('classroom_id', $classroomId)
                                        ->whereNotIn('id', $selectedStudents)
                                        ->pluck('nama', 'id');
                                })
                                ->searchable()
                                ->preload()
                                ->live()
                                ->required(),
                                Forms\Components\Radio::make('status')
                                    ->label('Status')
                                    ->options([
                                        'hadir' => 'Hadir',
                                        'izin' => 'Izin',
                                        'sakit' => 'Sakit',
                                        'alpha' => 'Alpha',
                                    ])
                                    ->inline()
                                    ->required(),
                            ])
                            ->addActionLabel('Tambah Siswa')
                            ->addable()
                            ->deletable()
                            ->reorderable(false)
                            ->columns(2),
                    ]),
            ])
            ->columns(1);
    }
}

