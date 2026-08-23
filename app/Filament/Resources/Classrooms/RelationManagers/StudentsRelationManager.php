<?php

namespace App\Filament\Resources\Classrooms\RelationManagers;

use App\Filament\Resources\ElementaryStudents\ElementaryStudentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;


class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    protected static ?string $relatedResource = ElementaryStudentResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('nama')
                    ->required(),
                Forms\Components\TextInput::make('nisn')
                    ->required(),
                Forms\Components\TextInput::make('nik')
                    ->required(),
                Forms\Components\TextInput::make('tempat_lahir')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->required(),
                Forms\Components\TextInput::make('tingkat_rombel')
                    ->required(),
                Forms\Components\TextInput::make('umur')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak aktif' => 'Tidak Aktif',
                    ])
                    ->required(),
                Forms\Components\Select::make('jenis_kelamin')
                    ->options([
                        'laki-laki' => 'Laki-laki',
                        'perempuan' => 'Perempuan',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('nomor_telepon')
                    ->tel()
                    ->required(),
                Forms\Components\TextInput::make('kebutuhan_khusus')
                    ->required(),
                Forms\Components\TextInput::make('disabilitas')
                    ->required(),
                Forms\Components\TextInput::make('nomor_kip_pip')
                    ->required(),
                Forms\Components\TextInput::make('nama_ayah')
                    ->required(),
                Forms\Components\TextInput::make('nama_ibu')
                    ->required(),
                Forms\Components\TextInput::make('nama_wali')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                Tables\Columns\TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('L/P')
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'tidak aktif' => 'danger',
                        default => 'gray',
                    }),
            ]);
    }
}
