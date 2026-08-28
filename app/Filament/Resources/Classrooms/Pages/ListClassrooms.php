<?php

namespace App\Filament\Resources\Classrooms\Pages;

use App\Filament\Resources\Classrooms\ClassroomResource;
use App\Imports\ClassroomsImport;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListClassrooms extends ListRecords
{
    protected static string $resource = ClassroomResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
                    try {
                        // Mengambil path absolut file dari storage
                        $filePath = Storage::disk('public')->path($data['attachment']);

                        // Jalankan proses import
                        Excel::import(new ClassroomsImport, $filePath);

                        Notification::make()
                            ->title('Import Berhasil!')
                            ->body('Data kelas berhasil ditambahkan / diperbarui.')
                            ->success()
                            ->send();

                    } catch (\Throwable $th) {
                        Notification::make()
                            ->title('Gagal Import Data')
                            ->body('Terjadi kesalahan: ' . $th->getMessage())
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),

            Actions\CreateAction::make(),
        ];
    }
}
