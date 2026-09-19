<?php

namespace App\Filament\Resources\AttendanceDetails\Pages;

use App\Filament\Resources\AttendanceDetails\AttendanceDetailResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceDetails extends ListRecords
{
    protected static string $resource = AttendanceDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
