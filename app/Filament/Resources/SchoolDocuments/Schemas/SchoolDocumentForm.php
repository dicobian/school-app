<?php

namespace App\Filament\Resources\SchoolDocuments\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SchoolDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                FileUpload::make('file_path')
                    ->label('Document')
                    ->directory('schoolDocuments')
                    ->disk('public')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'image/jpeg',
                        'image/png',
                    ])
                    ->maxSize(10240)
                    ->downloadable()
                    ->openable()
                    ->columnSpanFull()
            ]);
    }
}
