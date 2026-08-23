<?php

namespace App\Filament\Resources\ElementaryStudents\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ElementaryStudentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('classroom.name')
                    ->label('Classroom'),
                TextEntry::make('nama'),
                TextEntry::make('nisn'),
                TextEntry::make('nik'),
                TextEntry::make('tempat_lahir'),
                TextEntry::make('tanggal_lahir')
                    ->date(),
                TextEntry::make('tingkat_rombel'),
                TextEntry::make('umur'),
                TextEntry::make('status'),
                TextEntry::make('jenis_kelamin'),
                TextEntry::make('alamat')
                    ->columnSpanFull(),
                TextEntry::make('nomor_telepon'),
                TextEntry::make('kebutuhan_khusus'),
                TextEntry::make('disabilitas'),
                TextEntry::make('nomor_kip_pip'),
                TextEntry::make('nama_ayah'),
                TextEntry::make('nama_ibu'),
                TextEntry::make('nama_wali'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
