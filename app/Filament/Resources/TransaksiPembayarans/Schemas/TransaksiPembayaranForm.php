<?php

namespace App\Filament\Resources\TransaksiPembayarans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TransaksiPembayaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('student_id')
                    ->required()
                    ->numeric(),
                TextInput::make('nama_pembayaran')
                    ->required(),
                TextInput::make('kategori_pembayaran')
                    ->required(),
                Textarea::make('keterangan')
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('tanggal_bayar')
                    ->required(),
                TextInput::make('nominal')
                    ->required()
                    ->numeric(),
            ]);
    }
}
