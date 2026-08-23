<?php

namespace App\Filament\Resources\ElementaryStudents\RelationManagers;

use App\Filament\Resources\Bills\BillResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Schemas\Components;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Forms;
use Filament\Support\Enums\Width;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;


class BillsRelationManager extends RelationManager
{
    protected static string $relationship = 'bills';

    // protected static ?string $relatedResource = BillResource::class;
    //kalau mau pop up hapus baris ini

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([


                Forms\Components\TextInput::make('nama')
                    ->label('judul tagihan')
                    ->placeholder('Contoh: SPP Januari 2026')
                    ->required(),

                Forms\Components\TextInput::make('bulan_tahun')
                    ->label('Bulan / Tagihan')
                    ->placeholder('Contoh: SPP Januari 2026'),

                Forms\Components\TextInput::make('nominal')
                    ->label('Nominal (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status Pembayaran')
                    ->options([
                        'belum_lunas' => 'Belum Lunas',
                        'lunas' => 'Lunas',
                    ])
                    ->default('belum_lunas')
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_bayar')
                    ->label('Tanggal Bayar'),

                Forms\Components\TextArea::make('deskripsi')
                    ->label('Deskripsi')
                    ->placeholder('optional deskripsi'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('bulan_tahun')
            ->columns([
                Tables\Columns\TextColumn::make('bulan_tahun')
                    ->label('Bulan / Tagihan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nominal')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'lunas' => 'success',
                        'belum_lunas' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->label('Tgl Bayar')
                    ->date('d M Y'),
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('Tambah Tagihan SPP')
                    ->modalHeading('Tambah Tagihan Baru'),

                Action::make('pdf')
                    ->label('Cetak PDF')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($livewire) => route('bills.pdf', ['student' => $livewire->getOwnerRecord()->id]))
                    ->openUrlInNewTab() // Opsional: Buka di tab baru
                    // ->action(function (){
                    //     $pdf = Pdf::loadView('test');
                    //     return response()->streamDownload(
                    //         fn () => print($pdf->output()),
                    //         "Tagihan-test.pdf"
                    //     );
                    // })
            ])
            ->recordActions([
                Actions\EditAction::make()->modalHeading('Edit Tagihan'),
                Actions\DeleteAction::make(),
            ]);
    }
}
