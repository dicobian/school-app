<?php

namespace App\Filament\Resources\Items\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('warehouse_id')
                    ->relationship('warehouse', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
