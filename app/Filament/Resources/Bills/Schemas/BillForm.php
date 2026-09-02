<?php

namespace App\Filament\Resources\Bills\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use App\Models\ElementaryStudent;
use App\Models\Classroom;
use Filament\Support\RawJs;

class BillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('classroom_id')
                    ->label('Kelas')
                    ->options(Classroom::pluck('name', 'id')) // langsung dari model Classroom
                    ->live() // live ini agar setiap perubahan terjadi maka akan mentrigger fungsi yang diperlukan
                    ->dehydrated(false) // <-- PENTING: jangan disimpan ke tabel bills
                    ->afterStateUpdated(fn (Set $set) => $set('student_id', null)), // fungsi untuk mentrigger perubahan pada selection nama siswa, jika pilih kelas tertentu maka siswa yang ditampilkan hanya siswa yang dikelas tersebut aja

                Select::make('student_id')
                    ->label('Nama Siswa')
                    ->options(function (Get $get) {
                        $kelasId = $get('classroom_id');
                        if (!$kelasId) return [];
                        return ElementaryStudent::where('classroom_id', $kelasId)
                            ->pluck('nama', 'id');
                    })
                    ->live()
                    ->disabled(fn (Get $get) => !$get('classroom_id'))
                    ->required()
                    ->searchable(), // seaarchable ini menjadikan dropdown punya fitur pencarian

                TextInput::make('nama')
                    ->required(),
                Textarea::make('deskripsi')
                    ->default('-')
                    ->columnSpanFull(),
                TextInput::make('bulan_tahun'),
                TextInput::make('nominal')
                    ->required()
                    ->numeric()
                    ->mask(RawJs::make('$money($input, \'.\', \',\', 0)')) // fungsi menambahkan tanda pemisah ribuan untuk memudahkan
                    ->stripCharacters(',') // menghapus karakter pemisah yang ditambahkan sebelumnya agar terkirim 1000000 bukan 1.000.000
                    ->prefix('Rp'), //prefix tulisan pojok kiri di kolom
                Select::make('status')
                    ->options([
                        'belum_lunas' => 'Belum Lunas',
                        'lunas' => 'Lunas'
                    ])
                    ->default('belum_lunas'),
                DatePicker::make('tanggal_bayar'),
            ]);
    }
}
