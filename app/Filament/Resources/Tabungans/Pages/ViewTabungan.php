<?php

namespace App\Filament\Resources\Tabungans\Pages;

use App\Filament\Resources\Tabungans\TabunganResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTabungan extends ViewRecord
{
    protected static string $resource = TabunganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
