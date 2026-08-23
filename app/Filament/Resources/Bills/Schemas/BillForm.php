<?php

namespace App\Filament\Resources\Bills\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('student_id')
                    ->required()
                    ->numeric(),
                TextInput::make('nama')
                    ->required(),
                Textarea::make('deskripsi')
                    ->default('-')
                    ->columnSpanFull(),
                TextInput::make('bulan_tahun'),
                TextInput::make('nominal')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('belum_lunas'),
                DatePicker::make('tanggal_bayar'),
            ]);
    }
}
