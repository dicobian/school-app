<?php

namespace App\Filament\Resources\Warehouses\RelationManagers;

use App\Filament\Resources\Items\ItemResource;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms;
use Filament\Schemas\Schema;
use Livewire\Component;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $relatedResource = ItemResource::class;



    public function form(Schema $schema): Schema
    {
        return $schema->components(Forms\Components\TextInput::make('name')->required());
    }

    public function table(Table $table): Table
    {
        return $table->columns([Tables\Columns\TextColumn::make('name')]);
    }
}
