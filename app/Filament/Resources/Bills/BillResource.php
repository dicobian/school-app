<?php

namespace App\Filament\Resources\Bills;

use App\Filament\Resources\Bills\Pages\CreateBill;
use App\Filament\Resources\Bills\Pages\EditBill;
use App\Filament\Resources\Bills\Pages\ListBills;
use App\Filament\Resources\Bills\Pages\ViewBill;
use App\Filament\Resources\Bills\Schemas\BillForm;
use App\Filament\Resources\Bills\Schemas\BillInfolist;
use App\Filament\Resources\Bills\Tables\BillsTable;
use App\Models\Bill;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
class BillResource extends Resource
{
    protected static ?string $model = Bill::class;

    protected static string|BackedEnum|null $navigationIcon =  'heroicon-o-banknotes';
    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | UnitEnum | null $navigationGroup = 'keuangan';
    protected static ?string $navigationLabel = 'Tagihan';
    protected static ?string $modelLabel = 'Tagihan';
    protected static ?string $pluralModelLabel = 'Tagihan';

    // protected static ?string $recordTitleAttribute = 'Keuangan';

    public static function form(Schema $schema): Schema
    {
        return BillForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BillInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BillsTable::configure($table);
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
            'index' => ListBills::route('/'),
            'create' => CreateBill::route('/create'),
            'view' => ViewBill::route('/{record}'),
            'edit' => EditBill::route('/{record}/edit'),
        ];
    }
}
