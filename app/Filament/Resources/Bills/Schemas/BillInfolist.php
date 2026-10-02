<?php

namespace App\Filament\Resources\Bills\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BillInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('student_id')
                    ->numeric(),
                TextEntry::make('nama_tagihan'),
                TextEntry::make('keterangan'),
                TextEntry::make('tahun_ajaran'),
                TextEntry::make('nominal')
                    ->money('IDR'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);


    }
}
