<?php

namespace App\Filament\Resources\ElementaryStudents\Pages;

use App\Filament\Resources\ElementaryStudents\ElementaryStudentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewElementaryStudent extends ViewRecord
{
    protected static string $resource = ElementaryStudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
