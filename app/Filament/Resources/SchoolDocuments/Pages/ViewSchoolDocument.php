<?php

namespace App\Filament\Resources\SchoolDocuments\Pages;

use App\Filament\Resources\SchoolDocuments\SchoolDocumentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSchoolDocument extends ViewRecord
{
    protected static string $resource = SchoolDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
