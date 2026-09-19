<?php

namespace App\Filament\Resources\ElementaryStudents\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class ElementaryStudentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Student Information')
                    ->tabs([
                        Tab::make('Informasi Dasar')
                            ->icon(Heroicon::OutlinedUser)
                            ->schema([
                                TextEntry::make('classroom.name')
                                    ->label('Kelas'),
                                TextEntry::make('nama')
                                    ->label('Nama Lengkap'),
                                TextEntry::make('nisn')
                                    ->label('NISN'),
                                TextEntry::make('nik')
                                    ->label('NIK'),
                            ])
                            ->columns(2),

                        Tab::make('Informasi Lengkap')
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema([
                                TextEntry::make('tingkat_rombel')
                                    ->label('Tingkat/Rombel'),
                                TextEntry::make('umur')
                                    ->label('Umur'),
                                TextEntry::make('status')
                                    ->label('Status Siswa'),
                                TextEntry::make('kebutuhan_khusus')
                                    ->label('Kebutuhan Khusus')
                                    ->placeholder('-'),
                                TextEntry::make('disabilitas')
                                    ->label('Disabilitas')
                                    ->placeholder('-'),
                                TextEntry::make('nomor_kip_pip')
                                    ->label('Nomor KIP/PIP')
                                    ->placeholder('-'),
                                TextEntry::make('nama_ayah')
                                    ->label('Nama Ayah'),
                                TextEntry::make('nama_ibu')
                                    ->label('Nama Ibu'),
                                TextEntry::make('nama_wali')
                                    ->label('Nama Wali'),
                                TextEntry::make('tempat_lahir')
                                    ->label('Tempat Lahir'),
                                TextEntry::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->date(),
                                TextEntry::make('jenis_kelamin')
                                    ->label('Jenis Kelamin'),
                                TextEntry::make('nomor_telepon')
                                    ->label('Nomor Telepon'),
                                TextEntry::make('alamat')
                                    ->label('Alamat Lengkap')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
