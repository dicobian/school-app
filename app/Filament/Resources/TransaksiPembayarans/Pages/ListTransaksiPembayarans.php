<?php

namespace App\Filament\Resources\TransaksiPembayarans\Pages;

use App\Filament\Resources\TransaksiPembayarans\TransaksiPembayaranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransaksiPembayarans extends ListRecords
{
    protected static string $resource = TransaksiPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
