<?php

namespace App\Filament\Resources\Attendances;

use App\Filament\Resources\Attendances\Pages\CreateAttendance;
use App\Filament\Resources\Attendances\Pages\EditAttendance;
use App\Filament\Resources\Attendances\Pages\ListAttendances;
use App\Filament\Resources\Attendances\Pages\ViewAttendance;
use App\Filament\Resources\Attendances\Schemas\AttendanceForm;
use App\Filament\Resources\Attendances\Schemas\AttendanceInfolist;
use App\Filament\Resources\Attendances\Tables\AttendancesTable;
use App\Models\Attendance;
use App\Models\ElementaryStudent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Models\Student;
use Carbon\Carbon;

use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;

use UnitEnum;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Attendance';

    protected static string | UnitEnum | null $navigationGroup = 'Absensi';

    // public static function form(Schema $schema): Schema
    // {
    //     return AttendanceForm::configure($schema);
    // }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Absensi')
                    ->schema([
                        Select::make('classroom_id')
                            ->label('Kelas')
                            ->relationship('classroom', 'name')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if (! $state) return;

                                // Mengambil semua siswa aktif di kelas yang dipilih
                                $students = ElementaryStudent::where('classroom_id', $state)->get();

                                // Populasi otomatis repeater details dengan daftar siswa
                                $details = $students->map(fn ($student) => [
                                    'student_id' => $student->id,
                                    'status' => 'present',
                                ])->toArray();

                                $set('details', $details);
                            }),

                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal Absensi')
                            ->default(now())
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if ($state) {
                                    // Menampilkan nama hari secara otomatis
                                    $dayName = Carbon::parse($state)->translatedFormat('l');
                                    $set('day_display', $dayName);
                                }
                            }),

                        Forms\Components\TextInput::make('day_display')
                            ->label('Hari')
                            ->readOnly()
                            ->default(now()->translatedFormat('l')),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Opsional')
                            ->columnSpanFull(),
                    ])->columns(3),

                Section::make('Daftar Kehadiran Siswa')
                    ->schema([
                        Forms\Components\Repeater::make('details')
                            ->relationship('details')
                            ->schema([
                                Forms\Components\Select::make('student_id')
                                    ->label('Siswa')
                                    ->relationship('student', 'nama') // Ganti 'name' dengan kolom nama siswa
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),

                                Forms\Components\Radio::make('status')
                                    ->label('Status')
                                    ->options([
                                        'present' => 'Hadir',
                                        'late' => 'Terlambat',
                                        'excused' => 'Izin/Sakit',
                                        'absent' => 'Alpha',
                                    ])
                                    ->inline()
                                    ->required(),
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->columns(2),
                    ]),
            ])->columns(1);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AttendanceInfolist::configure($schema);
    }

    // public static function table(Table $table): Table
    // {
    //     return AttendancesTable::configure($table);
    // }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('classroom.name')
                ->label('Kelas')
                ->sortable()
                ->searchable(),

            TextColumn::make('date')
                ->label('Tanggal & Hari')
                ->date('d M Y (l)') // Menampilkan tanggal sekaligus nama hari
                ->sortable(),

            TextColumn::make('details_count')
                ->label('Total Siswa')
                ->counts('details'),
        ])
        ->filters([
            SelectFilter::make('classroom_id')
                ->label('Filter Kelas')
                ->relationship('classroom', 'name'),

            Filter::make('date')
                ->form([
                    Forms\Components\DatePicker::make('from')->label('Dari Tanggal'),
                    Forms\Components\DatePicker::make('until')->label('Sampai Tanggal'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['from'], fn ($q) => $q->whereDate('date', '>=', $data['from']))
                        ->when($data['until'], fn ($q) => $q->whereDate('date', '<=', $data['until']));
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
            'index' => ListAttendances::route('/'),
            'create' => CreateAttendance::route('/create'),
            'view' => ViewAttendance::route('/{record}'),
            'edit' => EditAttendance::route('/{record}/edit'),
        ];
    }
}
