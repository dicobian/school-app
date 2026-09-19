<?php

namespace App\Filament\Resources\Bills\Pages;

use App\Exports\BillsExport;
use App\Exports\FormatTagihan;
use App\Filament\Resources\Bills\BillResource;
use App\Imports\BillsImport;
use App\Models\Bill;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;

class ListBills extends ListRecords
{
    protected static string $resource = BillResource::class;

    protected function getHeaderActions(): array
    {
       return [
            CreateAction::make()
                ->label('Tambah Tagihan Baru'), // Mengubah teks tombol "New bill"
            Action::make('exportExcel')
                ->label('Export Data')
                ->icon('heroicon-o-document-arrow-down')
                ->color('info')
                ->action(fn () => Excel::download( new BillsExport(), 'data-keuangan-yayasan' . now()->format('Y-m-d') . '.xlsx')),
            Action::make('exportFormat')
                ->label('Format Pengisian Excel')
                ->icon('heroicon-o-document-text')
                ->color('danger')
                ->action(fn () => Excel::download(new FormatTagihan(), 'format-input-tagihan' . now()->format('Y-m-d') . '.xlsx')),
            Action::make('importExcel')
                ->label('Import Data')
                ->icon('heroicon-o-document-arrow-up')
                ->color('success')
                ->form([
                    FileUpload::make('attachment')
                    ->label('Pilih File Excel (.xlsx / .csv')
                    ->disk('public')
                    ->directory('imports')
                    ->acceptedFileTypes([
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-excel',
                        'text/csv',
                    ])
                    ->required()
                ])
                ->action(function (array $data): void {
                    $filePath = storage_path('app/public/' . $data['attachment']);
                    Excel::import(new BillsImport, $filePath);
                    Notification::make()
                        ->title('Import Berhasil')
                        ->body('Data berhasil di-import')
                        ->success()
                        ->send();
                })
        ];
    }
}
