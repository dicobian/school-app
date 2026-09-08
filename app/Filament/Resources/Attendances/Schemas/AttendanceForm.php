<?php

namespace App\Filament\Resources\Attendances\Schemas;

use App\Models\ElementaryStudent;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                        DatePicker::make('date')
                            ->label('Tanggal Absensi')
                            ->default(now())
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state){
                                if ($state) {
                                    $dayName = Carbon::parse($state)
                                        ->translatedFormat('l');
                                    $set('day_display', $dayName);
                                }
                            }),
                        TextInput::make('day_display')
                            ->label('Hari')
                            ->readOnly()
                            ->default(now()->translatedFormat('l')),
                        Textarea::make('notes')
                            ->label('Catatan Opsional')
                            ->columnSpanFull(),
                    ])->columns(3),

                Section::make('Daftar Kehadiran Siswa')
                    ->schema([
                        Repeater::make('details')
                            ->relationship('details')
                            ->schema([
                                Select::make('student_id')
                                    ->label('Siswa')
                                    ->options(function (Get $get) {
                                        $classroomId = $get('../../classroom_id');
                                        if(! $classroomId) {
                                            return [];
                                        }
                                        $currentStudent = $get('student_id');
                                        $selectedStudents = collect(
                                            $get('../../details') ?? []
                                        )
                                            ->pluck('student_id')
                                            ->filter()
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
                                        Radio::make('status')
                                            ->label('Status')
                                            ->options([
                                                'hadir' => 'Hadir',
                                                'izin' => 'Izin',
                                                'sakit' => 'Sakit',
                                                'alpha' => 'Alpha'
                                            ])
                                            ->inline()
                                            ->required(),
                            ])
                            ->addActionLabel('Tambah Siswa')
                            ->addable()
                            ->deletable()
                            ->reorderable(false)
                            ->columns(2),
                    ])

                    ])
                    ->columns(1);


    }
}
