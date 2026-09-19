<?php

namespace App\Filament\Resources\ElementaryStudents\Pages;

use App\Exports\ElementaryStudentExport;
use App\Filament\Resources\ElementaryStudents\ElementaryStudentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Imports\StudentsImport2;
use App\Imports\Studentsimport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use App\Models\ElementaryStudent;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Filament\Actions\Action;


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
            Action::make('importExcel')
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
                    Excel::import(new StudentsImport, $filePath);

                    Notification::make()
                        ->title('Import Berhasil')
                        ->body('Data siswa berhasil di-import. Data yang sudah ada diabaikan otomatis.')
                        ->success()
                        ->send();
                }),
            Action::make('exportExcel')
            ->label('Export Data Siswa')
            ->icon('heroicon-o-document-arrow-down')
            ->color('info')
            ->action(fn () => Excel::Download( new ElementaryStudentExport, 'data-seluruh-siswa' . now()->format('Y-m-d') . '.xlsx')),
            
            CreateAction::make()->label('New Siswa'),

            Action::make('cetak_id_card')
                ->label('Cetak ID Card')
                ->icon('heroicon-o-identification')
                ->action(function () {
                    $students = ElementaryStudent::with('classroom')->get();
                    $writer = new PngWriter();
                    foreach($students as $student) {
                        $qrCode = new QrCode($student->barcode);
                        $result = $writer->write($qrCode);
                        $student->qr_data_uri = $result->getDataUri();
                    }

                    $pdf = Pdf::loadView('pdf.id-card', ['students' => $students])->setPaper('a4', 'potrait');
                    return response()->streamDownload(fn () => print($pdf->output()), 'id-card-siswa.pdf');
                })
        ];
    }
}
