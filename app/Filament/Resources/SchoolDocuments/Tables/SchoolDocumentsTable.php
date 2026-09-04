<?php

namespace App\Filament\Resources\SchoolDocuments\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SchoolDocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Dokumen')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('')
                    ->dateTime()
                    ->sortable()
                    // ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('preview') // fitur preview dokumen 
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalHeading(fn ($record) => $record->title)
                    ->modalContent(fn ($record) => view('filament.pages.pdf-preview', [
                        'url' => asset('storage/' . $record->file_path),
                    ]))
                    ->modalWidth('5xl')
                    ->modalSubmitAction(false) // hilangkan tombol submit, cuma preview
                    ->modalCancelActionLabel('Tutup'),
                Action::make('download')
                    ->label('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn ($record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab()
                // ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
