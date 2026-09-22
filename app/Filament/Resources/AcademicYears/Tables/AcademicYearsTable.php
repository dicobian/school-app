<?php

namespace App\Filament\Resources\AcademicYears\Tables;

use App\Models\AcademicYear;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;
class AcademicYearsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable()
                    ->badge(true)
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'arsip' => 'gray',
                        'akan_datang' => 'gray',
                    }),
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
                //
            ])
            ->recordActions([
                ViewAction::make()->modal(),
                EditAction::make()->modal(),
                Action::make('aktifkan')
                    ->label('Aktifkan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (AcademicYear $record) => $record->status !== 'aktif')
                    ->requiresConfirmation()
                    ->modalheading('Aktifkan Tahun Ajaran ini ? ')
                    ->modalDescription('Tahun ajaran yang sedang aktif akan otomatis dijadikan arsip')
                    ->action(function (AcademicYear $record) {
                        $record->activate(); //activate fungsi buatan di model AcademicYear

                        Notification::make()
                        ->title("Tahun ajaran {$record->nama} diaktifkan")
                        ->success()
                        ->send();

                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
