<?php

namespace App\Filament\Resources\TransaksiPembayarans\Pages;

use App\Filament\Resources\TransaksiPembayarans\TransaksiPembayaranResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTransaksiPembayaran extends EditRecord
{
    protected static string $resource = TransaksiPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
