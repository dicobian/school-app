<?php

namespace App\Filament\Resources\Classrooms\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ClassroomInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('deskripsi')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
