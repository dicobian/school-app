<?php

namespace App\Filament\Resources\AcademicYears\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AcademicYearForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Tahun Ajaran')
                    ->placeholder('tulis seperti ini :2026/2027'),
                Select::make('status')
                    ->label('status')
                    ->options([
                        'arsip' => 'Arsip',
                        'akan_datang' => 'Akan Datang'
                    ])
                    ->default('akan_datang'),
            ]);
    }
}
