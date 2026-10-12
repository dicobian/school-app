<?php

namespace App\Filament\Resources\ElementaryStudents\RelationManagers;


use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Support\RawJs;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use App\Models\Bill;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\Summarizers\Sum;



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


               Select::make('nama_tagihan')
                    ->options([
                        'infaq_bulanan_spp'          => 'Infaq Bulanan / Spp',//1
                        'administrasi_murid_baru'    => 'Administrasi Murid Baru',//2
                        'administrasi_daftar_ulang'  => 'Administrasi Daftar Ulang',//3
                        'administrasi_kelas_6'       => 'Administrasi Kelas 6',//4
                        'buku_paket_1'               => 'Buku Paket 1',//5
                        'buku_paket_2'               => 'Buku Paket 2',//6
                        'foto_ijazah'                => 'Foto Ijazah',//7
                        'akhiru_sanah'               => 'Akhiru Sanah',//8
                        'pendaftaran_murid_baru'     => 'Pendaftaran Murid Baru',//9
                        'tunggakan_lain' => 'Tunggakan Lain',
                    ])
                    ->live()
                    ->afterStateUpdated(function(Set $set, ?string $state){ //tanda tanya sebelum parameter artinya paramter tersebut boleh null
                        $nominal = Bill::NOMINAL_TAGIHAN[$state] ?? null;
                        if ($nominal!== null){
                            $set('nominal', $nominal);
                        }
                    })
                    ->label('jenis tagihan')
                    ->required(),
                TextInput::make('keterangan'),
                TextInput::make('nominal')
                    ->required()
                    ->numeric()
                    ->mask(RawJs::make('$money($input, \'.\', \',\', 0)')) // fungsi menambahkan tanda pemisah ribuan untuk memudahkan
                    ->stripCharacters(',') // menghapus karakter pemisah yang ditambahkan sebelumnya agar terkirim 1000000 bukan 1.000.000
                    ->prefix('Rp'), //prefix tulisan pojok kiri di kolom
                TextInput::make('tahun_ajaran')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_tagihan')
            ->description(function () {
                $student = $this->getOwnerRecord();
                $total = $student->sisa_tagihan; //acceessor dipanggil tanpa tanda kurung, sementara method tetap pakai tanda kurung

                return new \Illuminate\Support\HtmlString(

                    '<div class="text-lg font-bold text-danger-600">'
                    . 'Tunggakan: Rp ' . number_format($total, 0, ',', '.')
                    . '</div>'
                );
            })
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal input'),
                TextColumn::make('nama_tagihan')
                    ->formatStateUsing(fn (?string $state) => $state ? str_replace('_', ' ', $state) : $state)
                    ->label('Jenis Tagihan'),
                TextColumn::make('tahun_ajaran')
                    ->label('Tahun Ajaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nominal')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),
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
                    //     $pdf = Pdf::loadView('pdf.print');
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
