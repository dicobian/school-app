<?php

namespace App\Filament\Resources\ElementaryStudents\Pages;

use App\Filament\Resources\ElementaryStudents\ElementaryStudentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListElementaryStudents extends ListRecords
{
    protected static string $resource = ElementaryStudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
