<?php

namespace App\Filament\Resources\Tabungans\Schemas;

use App\Models\Classroom;
use App\Models\ElementaryStudent;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\RawJs;

class TabunganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('student_id')
                //     ->required()
                //     ->numeric(),
                Select::make('classroom_id')
                    ->label("Kelas")
                    ->options(Classroom::pluck("name", 'id'))
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(fn (Set $set) => $set('student_id', null)),
                Select::make('student_id')
                    ->label("siswa")
                    ->options(function (Get $get){
                        $kelasId = $get('classroom_id');
                        if (!$kelasId) return [];
                        return ElementaryStudent::where('classroom_id', $kelasId)
                            ->pluck('nama', 'id');
                    })
                    ->live()
                    ->disabled(fn (Get $get) => !$get('classroom_id'))
                    ->required()
                    ->searchable(),
                Select::make('jenis_transaksi_tabungan')
                    ->options(
                    [
                    'menabung' => 'menabung',
                    'penarikan' => 'penarikan'
                    ]),
                TextInput::make('nominal')
                    ->required()
                    ->mask(RawJs::make('$money($input, \'.\', \',\', 0)'))
                    ->stripCharacters(',')
                    ->prefix('Rp')
                    ->numeric(),
                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->columnSpanFull()
            ]);
    }
}
