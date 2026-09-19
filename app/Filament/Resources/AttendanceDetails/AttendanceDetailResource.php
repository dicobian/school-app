<?php

namespace App\Filament\Resources\AttendanceDetails;

use App\Filament\Resources\AttendanceDetails\Pages\CreateAttendanceDetail;
use App\Filament\Resources\AttendanceDetails\Pages\EditAttendanceDetail;
use App\Filament\Resources\AttendanceDetails\Pages\ListAttendanceDetails;
use App\Filament\Resources\AttendanceDetails\Pages\ViewAttendanceDetail;
use App\Filament\Resources\AttendanceDetails\Schemas\AttendanceDetailForm;
use App\Filament\Resources\AttendanceDetails\Schemas\AttendanceDetailInfolist;
use App\Filament\Resources\AttendanceDetails\Tables\AttendanceDetailsTable;
use App\Models\AttendanceDetail;
use BackedEnum;
use Dom\Text;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class AttendanceDetailResource extends Resource
{
    protected static ?string $model = AttendanceDetail::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // protected static ?string $recordTitleAttribute = 'attendance_detail';

    protected static string | UnitEnum | null $navigationGroup = 'Absensi';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return AttendanceDetailForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AttendanceDetailInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendanceDetailsTable::configure($table);


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
            'index' => ListAttendanceDetails::route('/'),
            'create' => CreateAttendanceDetail::route('/create'),
            'view' => ViewAttendanceDetail::route('/{record}'),
            'edit' => EditAttendanceDetail::route('/{record}/edit'),
        ];
    }
}
