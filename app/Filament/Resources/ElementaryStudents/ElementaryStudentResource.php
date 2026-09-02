<?php

namespace App\Filament\Resources\ElementaryStudents;

use App\Filament\Resources\ElementaryStudents\Pages\CreateElementaryStudent;
use App\Filament\Resources\ElementaryStudents\Pages\EditElementaryStudent;
use App\Filament\Resources\ElementaryStudents\Pages\ListElementaryStudents;
use App\Filament\Resources\ElementaryStudents\Pages\ViewElementaryStudent;
use App\Filament\Resources\ElementaryStudents\RelationManagers\BillsRelationManager;
use App\Filament\Resources\ElementaryStudents\Schemas\ElementaryStudentForm;
use App\Filament\Resources\ElementaryStudents\Schemas\ElementaryStudentInfolist;
use App\Filament\Resources\ElementaryStudents\Tables\ElementaryStudentsTable;
use App\Models\ElementaryStudent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;

use Filament\Infolists\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;



class ElementaryStudentResource extends Resource
{
    protected static ?string $model = ElementaryStudent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Main Data SD';
    protected static ?string $navigationLabel = 'Siswa';
    protected static ?string $modelLabel = 'Siswa';
    protected static ?string $pluralModelLabel = 'Siswa';

    // protected static ?string $recordTitleAttribute = 'ElementaryStudent';

    public static function form(Schema $schema): Schema
    {
        return ElementaryStudentForm::configure($schema);

    }

    public static function infolist(Schema $schema): Schema
    {
        return ElementaryStudentInfolist::configure($schema);

    }

    public static function table(Table $table): Table
    {
        return ElementaryStudentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            'bills' => RelationManagers\BillsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListElementaryStudents::route('/'),
            'create' => CreateElementaryStudent::route('/create'),
            'view' => ViewElementaryStudent::route('/{record}'),
            'edit' => EditElementaryStudent::route('/{record}/edit'),
        ];
    }
}
