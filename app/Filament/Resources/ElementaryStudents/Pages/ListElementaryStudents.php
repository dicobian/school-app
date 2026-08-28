<?php

namespace App\Filament\Resources\ElementaryStudents\Pages;

use App\Filament\Resources\ElementaryStudents\ElementaryStudentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Imports\StudentsImport2;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;

class ListElementaryStudents extends ListRecords
{
    protected static string $resource = ElementaryStudentResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         CreateAction::make(),
    //     ];
    // }

    protected function getHeaderActions(): array
    {
        return [
            // Tombol Import Excel Custom
            Actions\Action::make('importExcel')
                ->label('Import Excel')
                ->icon('heroicon-o-document-arrow-up')
                ->color('success')
                ->form([
                    FileUpload::make('attachment')
                        ->label('Pilih File Excel (.xlsx / .csv)')
                        ->disk('public')
                        ->directory('imports')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            'text/csv',
                        ])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $filePath = storage_path('app/public/' . $data['attachment']);

                    // Jalankan Import
                    Excel::import(new StudentsImport2, $filePath);

                    Notification::make()
                        ->title('Import Berhasil')
                        ->body('Data siswa berhasil di-import. Data yang sudah ada diabaikan otomatis.')
                        ->success()
                        ->send();
                }),

            Actions\CreateAction::make()->label('New Siswa'),
        ];
    }
}
