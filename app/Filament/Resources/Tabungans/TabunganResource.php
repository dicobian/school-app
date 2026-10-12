<?php

namespace App\Filament\Resources\Tabungans;

use App\Filament\Resources\Tabungans\Pages\CreateTabungan;
use App\Filament\Resources\Tabungans\Pages\EditTabungan;
use App\Filament\Resources\Tabungans\Pages\ListTabungans;
use App\Filament\Resources\Tabungans\Pages\ViewTabungan;
use App\Filament\Resources\Tabungans\Schemas\TabunganForm;
use App\Filament\Resources\Tabungans\Schemas\TabunganInfolist;
use App\Filament\Resources\Tabungans\Tables\TabungansTable;
use App\Models\Tabungan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TabunganResource extends Resource
{
    protected static ?string $model = Tabungan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string| UnitEnum | null $navigationGroup = 'keuangan';
    protected static ?string $recordTitleAttribute = 'Tabungan';
    protected static ?string $navigationLabel = 'Tabungan';
    protected static ?string $modelLabel = 'Tabungan';
    protected static ?string $pluralModelLabel = 'Tabungan';

    public static function form(Schema $schema): Schema
    {
        return TabunganForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TabunganInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TabungansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTabungans::route('/'),
            'create' => CreateTabungan::route('/create'),
            'view' => ViewTabungan::route('/{record}'),
            'edit' => EditTabungan::route('/{record}/edit'),
        ];
    }
}
