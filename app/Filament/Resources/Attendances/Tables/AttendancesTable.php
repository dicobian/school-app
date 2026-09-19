<?php

namespace App\Filament\Resources\Attendances\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
         return $table
        ->columns([
            TextColumn::make('classroom.name')
                ->label('Kelas')
                ->sortable()
                ->searchable(),

            TextColumn::make('date')
                ->label('Tanggal & Hari')
                ->date('d M Y (l)') // Menampilkan tanggal sekaligus nama hari
                ->sortable(),

            TextColumn::make('details_count')
                ->label('Total Siswa')
                ->counts('details'),
        ])
        ->filters([
            SelectFilter::make('classroom_id')
                ->label('Filter Kelas')
                ->relationship('classroom', 'name'),

            Filter::make('date')
                ->form([
                    Forms\Components\DatePicker::make('from')->label('Dari Tanggal'),
                    Forms\Components\DatePicker::make('until')->label('Sampai Tanggal'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['from'], fn ($q) => $q->whereDate('date', '>=', $data['from']))
                        ->when($data['until'], fn ($q) => $q->whereDate('date', '<=', $data['until']));
                }),
        ]);
    }
}
