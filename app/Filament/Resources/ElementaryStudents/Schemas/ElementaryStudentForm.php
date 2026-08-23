<?php

namespace App\Filament\Resources\ElementaryStudents\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ElementaryStudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('classroom_id')
                    ->relationship('classroom', 'name')
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('nisn')
                    ->required(),
                TextInput::make('nik')
                    ->required(),
                TextInput::make('tempat_lahir')
                    ,
                DatePicker::make('tanggal_lahir')
                    ,
                TextInput::make('tingkat_rombel')
                    ,
                TextInput::make('umur')
                    ,
                Select::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak aktif' => 'Tidak Aktif',
                    ])
                    ,
                Select::make('jenis_kelamin')
                    ->options([
                        'laki-laki' => 'Laki-laki',
                        'perempuan' => 'Perempuan',
                    ])
                    ,
                Textarea::make('alamat')

                    ->columnSpanFull(),
                TextInput::make('nomor_telepon')
                    ->tel()
                    ,
                TextInput::make('kebutuhan_khusus')
                    ,
                TextInput::make('disabilitas')
                    ,
                TextInput::make('nomor_kip_pip')
                    ,
                TextInput::make('nama_ayah')
                    ,
                TextInput::make('nama_ibu')
                    ,
                TextInput::make('nama_wali')
                    ,
            ]);
    }
}
