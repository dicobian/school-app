<?php

namespace App\Filament\Resources\SchoolDocuments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SchoolDocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Judul Dokumen'),
                TextEntry::make('description')
                    ->label('Deskripsi')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime('d F Y, H:i')
                    ->placeholder('-'),
                TextEntry::make('file')
                    ->formatStateUsing(fn () => 'Lihat / Unduh Dokumen')
                    ->url(fn ($record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->icon('heroicon-o-document-arrow-down'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
