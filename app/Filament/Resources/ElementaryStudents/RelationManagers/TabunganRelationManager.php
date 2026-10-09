<?php

namespace App\Filament\Resources\ElementaryStudents\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;
use App\Models\Classroom;
use App\Models\ElementaryStudent;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\RawJs;
use Illuminate\Support\HtmlString;

class TabunganRelationManager extends RelationManager
{
    protected static string $relationship = 'tabungan';
    #[Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('student_id')
                //     ->required()
                //     ->numeric(),
                Select::make('jenis_transaksi_tabungan')
                    ->options(
                    [
                    'menabung' => 'menabung',
                    'penarikan' => 'penarikan'
                    ]),
                TextInput::make('nominal')
                    ->required()
                    ->mask(RawJs::make('$money($input, \'.\', \',\', 0)'))
                    ->stripCharacters(',')
                    ->prefix('Rp')
                    ->numeric(),
                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->columnSpanFull()
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('jenis_transaksi_tabungan'),
                TextEntry::make('nominal')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('keterangan')
                    ->placeholder('-'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->recordTitleAttribute('jenis_transaksi_tabungan')
            ->description(function () {
                $student = $this->getOwnerRecord();
                $tabungan = $student->total_tabungan;
                return new HtmlString(
                    '<div class="text-lg font-bold text-danger-600">'
                    .'Total Tabungan: Rp ' . number_format($tabungan, 0, ',', '.')
                    .'</div>'
                );
            })
            ->columns([
                TextColumn::make('jenis_transaksi_tabungan')
                    ->searchable(),
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
                TextColumn::make('keterangan')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
