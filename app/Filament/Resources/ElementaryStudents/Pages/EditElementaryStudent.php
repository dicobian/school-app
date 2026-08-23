<?php

namespace App\Filament\Resources\ElementaryStudents\Pages;

use App\Filament\Resources\ElementaryStudents\ElementaryStudentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditElementaryStudent extends EditRecord
{
    protected static string $resource = ElementaryStudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return $this->getRecord()->nama;
    }
}
