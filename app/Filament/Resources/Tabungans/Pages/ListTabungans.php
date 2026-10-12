<?php

namespace App\Filament\Resources\Tabungans\Pages;

use App\Filament\Resources\Tabungans\TabunganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTabungans extends ListRecords
{
    protected static string $resource = TabunganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
