<?php

namespace App\Filament\Widgets;

use App\Models\Bill;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestUnpaidBills extends BaseWidget
{
    protected static ?string $heading = 'Tagihan Belum Lunas Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Bill::query()
                    ->where('status', 'belum_lunas')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('students.nama')
                    ->label('Siswa'),
                TextColumn::make('students.classroom.name')
                    ->label('Kelas'),
                TextColumn::make('nama_tagihan')
                    ->formatStateUsing(fn (?string $state) => $state ? str_replace('_', ' ', $state) : $state)
                    ->label('Tagihan'),
                TextColumn::make('nominal')
                    ->label('Nominal')
                    ->money('IDR'),
            ])
            ->paginated(false);
    }
}
