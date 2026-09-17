<?php

namespace App\Filament\Resources\Bills\Pages;

use App\Exports\BillsExport;
use App\Filament\Resources\Bills\BillResource;
use App\Models\Bill;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Actions\Action;

class ListBills extends ListRecords
{
    protected static string $resource = BillResource::class;

    protected function getHeaderActions(): array
    {
       return [
            CreateAction::make()
                ->label('Tambah Tagihan Baru'), // Mengubah teks tombol "New bill"
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->color('info')
                ->action(fn () => Excel::Download( new BillsExport, 'data-keuangan-yayasan' . now()->format('Y-m-d')  . '.xlsx'))
        ];
    }
}
