<?php

namespace App\Filament\Resources\TransaksiPembayarans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TransaksiPembayaranInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('student_id')
                    ->numeric(),
                TextEntry::make('nama_pembayaran'),
                TextEntry::make('kategori_pembayaran'),
                TextEntry::make('keterangan')
                    ->columnSpanFull(),
                TextEntry::make('tanggal_bayar')
                    ->date(),
                TextEntry::make('nominal')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
