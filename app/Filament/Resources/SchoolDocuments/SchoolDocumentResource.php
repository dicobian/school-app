<?php

namespace App\Filament\Resources\SchoolDocuments;

use App\Filament\Resources\SchoolDocuments\Pages\CreateSchoolDocument;
use App\Filament\Resources\SchoolDocuments\Pages\EditSchoolDocument;
use App\Filament\Resources\SchoolDocuments\Pages\ListSchoolDocuments;
use App\Filament\Resources\SchoolDocuments\Pages\ViewSchoolDocument;
use App\Filament\Resources\SchoolDocuments\Schemas\SchoolDocumentForm;
use App\Filament\Resources\SchoolDocuments\Schemas\SchoolDocumentInfolist;
use App\Filament\Resources\SchoolDocuments\Tables\SchoolDocumentsTable;
use App\Models\SchoolDocument;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SchoolDocumentResource extends Resource
{
    protected static ?string $model = SchoolDocument::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Dokumen';
    protected static string | UnitEnum | null $navigationGroup = 'Berkas';
    protected static ?string $navigationLabel = 'Dokumen';
    protected static ?string $modelLabel = 'Dokumen';
    protected static ?string $pluralModelLabel = 'Dokumen';

    public static function form(Schema $schema): Schema
    {
        return SchoolDocumentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SchoolDocumentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SchoolDocumentsTable::configure($table);
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
            'index' => ListSchoolDocuments::route('/'),
            'create' => CreateSchoolDocument::route('/create'),
            'view' => ViewSchoolDocument::route('/{record}'),
            'edit' => EditSchoolDocument::route('/{record}/edit'),
        ];
    }
}
