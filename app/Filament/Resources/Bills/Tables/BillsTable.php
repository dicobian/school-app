<?php

namespace App\Filament\Resources\Bills\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;


class BillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('students.nama')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nama_tagihan')
                    ->searchable(),
                TextColumn::make('bulan'),
                TextColumn::make('tahun_ajaran')
                    ->searchable(),
                TextColumn::make('nominal')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('tanggal_bayar')
                    ->date()
                    ->sortable(),
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
                SelectFilter::make('status')
                ->label('Lunas / Belum Lunas')
                ->options([
                    'lunas' => 'lunas',
                    'belum_lunas' => 'belum lunas'
                ])
                ->preload()
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);


    }
}
