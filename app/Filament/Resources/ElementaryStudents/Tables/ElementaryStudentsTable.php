<?php

namespace App\Filament\Resources\ElementaryStudents\Tables;

use App\Http\Controllers\BillsController;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\BulkAction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use ZipArchive;
use Illuminate\Support\Facades\Storage;


class ElementaryStudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row_number')
                    ->label('#')
                    ->rowIndex(),
                TextColumn::make('classroom.name')
                    ->sortable()
                    ,
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable()
                    ,
                TextColumn::make('nisn')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nik')
                    ->searchable()
                    ,
                TextColumn::make('tempat_lahir')
                    ->searchable()
                    ,
                TextColumn::make('tanggal_lahir')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tingkat_rombel')
                    ->searchable()
                    ,
                TextColumn::make('umur')
                    ->searchable()
                    ,
                TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'tidak aktif' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('jenis_kelamin')
                    ->searchable()
                    ,
                TextColumn::make('nomor_telepon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('kebutuhan_khusus')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('disabilitas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nomor_kip_pip')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nama_ayah')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nama_ibu')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nama_wali')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('classroom')
                    ->label('kelas')
                    ->relationship('classroom', 'name')
                    ->searchable()
                    ->preload(),
                Filter::make('punya_tunggakan')
                    ->label('Siswa Yang Punya Tunggakan')
                    ->query(fn ($query) => $query->whereHas('bills', fn ($q) => $q->where('status', 'belum_lunas'))),

            ])
            // ->recordUrl(
            //     fn (Model $record): string => route('filament.admin.resources.elementary-students.edit', ['record' => $record]),
            // )
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('cetakTagihanMassal')
                    ->label('Cetak Tagihan Zip')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        return app(BillsController::class)->printBulkPdf($records);
                    })
                    ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
