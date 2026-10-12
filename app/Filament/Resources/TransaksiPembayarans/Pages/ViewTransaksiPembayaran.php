<?php

namespace App\Filament\Resources\TransaksiPembayarans\Pages;

use App\Filament\Resources\TransaksiPembayarans\TransaksiPembayaranResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTransaksiPembayaran extends ViewRecord
{
    protected static string $resource = TransaksiPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
