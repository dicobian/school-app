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
                        'infaq_bulanan_spp' => 'Infaq Bulanan / SPP',
                        'buku' => 'Uang Buku',
                        'daftar_ulang' => 'Daftar Ulang',
                        'administrasi_kelas_6',
                        'foto' => 'Foto',
                        'rihlah' => 'Rihlah',
                        'akhiru_sanah' => 'Akhiru Sanah',
                        'pendaftaran_murid_baru' => 'Pendaftaran Murid Baru',
                        'administrasi_murid_baru' => 'Administrasi Murid Baru',
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
                TextInput::make('nominal')
                    ->required()
                    ->numeric()
                    ->mask(RawJs::make('$money($input, \'.\', \',\', 0)')) // fungsi menambahkan tanda pemisah ribuan untuk memudahkan
                    ->stripCharacters(',') // menghapus karakter pemisah yang ditambahkan sebelumnya agar terkirim 1000000 bukan 1.000.000
                    ->prefix('Rp'), //prefix tulisan pojok kiri di kolom
                TextInput::make('tahun_ajaran')
                    ->required(),
                Select::make('bulan')
                    ->options([
                        'januari' => 'Januari',
                        'februari' => 'Februari',
                        'maret' => 'Maret',
                        'april' => 'April',
                        'mei' => 'Mei',
                        'juni' => 'Juni',
                        'juli' => 'Juli',
                        'agustus' => 'Agustus',
                        'september' => 'September',
                        'oktober' => 'Oktober',
                        'november' => 'November',
                        'desember' => 'Desember'
                    ])->required(),

                Select::make('status')
                    ->options([
                        'belum_lunas' => 'Belum Lunas',
                        'lunas' => 'Lunas'
                    ])
                    ->live()
                    ->afterStateUpdated(function (Set $set, ?string $state){
                        if($state == 'lunas') {
                            $set('tanggal_bayar', now()->toDateString());
                        } else {
                            $set('tanggal_bayar', null);
                        }
                    })
                    ->default('belum_lunas'),
                DatePicker::make('tanggal_bayar'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('bulan_tahun')
            ->description(function () {
            $bills = $this->getOwnerRecord()->bills;
            $total = $bills->sum('nominal');
            $lunas = $bills->where('status', 'lunas')->sum('nominal');
            $belum = $bills->where('status', 'belum_lunas')->sum('nominal');

            return new \Illuminate\Support\HtmlString(

                '<div class="text-lg font-bold text-danger-600">'
                . 'Tunggakan: Rp ' . number_format($belum, 0, ',', '.')
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
                TextColumn::make('bulan')
                    ->label('Bulan'),
                TextColumn::make('nominal')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (?string $state) => $state ? str_replace('_', ' ', $state) : $state)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'lunas' => 'success',
                        'belum_lunas' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('tanggal_bayar')
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
