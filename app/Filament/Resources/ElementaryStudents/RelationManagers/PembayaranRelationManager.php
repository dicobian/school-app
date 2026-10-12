<?php

namespace App\Filament\Resources\ElementaryStudents\RelationManagers;

use App\Models\TransaksiPembayaran;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class PembayaranRelationManager extends RelationManager
{
    protected static string $relationship = 'pembayaran';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kategori_pembayaran')
                    ->options(TransaksiPembayaran::JENIS_PEMBAYARAN),
                TextInput::make('nama_pembayaran')
                    ->required(),
                DatePicker::make('tanggal_bayar')
                    ->required(),
                TextInput::make('nominal')
                    ->required()
                    ->numeric()
                    ->mask(RawJs::make('$money($input, \'.\', \',\', 0)'))
                    ->stripCharacters(',')
                    ->prefix('Rp'),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kategori_pembayaran'),
                TextEntry::make('nama_pembayaran'),
                TextEntry::make('tanggal_bayar')
                    ->date(),
                TextEntry::make('nominal')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_pembayaran')
                ->description(function () {
                    $student = $this->getOwnerRecord();
                    $total = $student->sisa_saldo; //acceessor dipanggil tanpa tanda kurung, sementara method tetap pakai tanda kurung

                    return new \Illuminate\Support\HtmlString(

                        '<div class="text-lg font-bold text-danger-600">'
                        . 'Sisa Uang Pembayaran: Rp ' . number_format($total, 0, ',', '.')
                        . '</div>'
                    );
                })
            ->recordTitleAttribute('nama_pembayaran')
            ->columns([
                TextColumn::make('kategori_pembayaran')
                    ->searchable(),
                TextColumn::make('nama_pembayaran')
                    ->searchable(),
                TextColumn::make('tanggal_bayar')
                    ->date()
                    ->sortable(),
                TextColumn::make('nominal')
                    ->numeric()
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
                //
            ])
            ->headerActions([
                CreateAction::make()
                ->label('Tambah Pembayaran'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
