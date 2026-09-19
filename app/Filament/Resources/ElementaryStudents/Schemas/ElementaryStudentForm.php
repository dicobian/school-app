<?php

namespace App\Filament\Resources\ElementaryStudents\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;


use App\Filament\Resources\ElementaryStudents\Pages\CreateElementaryStudent;
use App\Filament\Resources\ElementaryStudents\Pages\EditElementaryStudent;
use App\Filament\Resources\ElementaryStudents\Pages\ListElementaryStudents;
use App\Filament\Resources\ElementaryStudents\Pages\ViewElementaryStudent;
use App\Filament\Resources\ElementaryStudents\RelationManagers\BillsRelationManager;
use App\Filament\Resources\ElementaryStudents\Schemas\ElementaryStudentInfolist;
use App\Filament\Resources\ElementaryStudents\Tables\ElementaryStudentsTable;
use App\Models\ElementaryStudent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class ElementaryStudentForm
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
                                Select::make('classroom_id')
                                    ->label('Kelas')
                                    ->relationship('classroom', 'name')
                                    ->required(),

                                TextInput::make('nama')
                                    ->label('Nama Lengkap')
                                    ->required(),

                                TextInput::make('nisn')
                                    ->label('NISN')
                                    ->required(),

                                TextInput::make('nik')
                                    ->label('NIK')
                                    ->required(),


                            ])
                            ->columns(2),

                        Tab::make('Informasi Lengkap')
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema([

                                TextInput::make('tingkat_rombel')
                                    ->label('Tingkat/Rombel'),

                                TextInput::make('umur')
                                    ->label('Umur'),

                                Select::make('status')
                                    ->label('Status Siswa')
                                    ->options([
                                        'aktif' => 'Aktif',
                                        'tidak aktif' => 'Tidak Aktif',
                                    ]),

                                TextInput::make('kebutuhan_khusus')
                                    ->label('Kebutuhan Khusus'),

                                TextInput::make('disabilitas')
                                    ->label('Disabilitas'),

                                TextInput::make('nomor_kip_pip')
                                    ->label('Nomor KIP/PIP'),

                                TextInput::make('nama_ayah')
                                    ->label('Nama Ayah'),

                                TextInput::make('nama_ibu')
                                    ->label('Nama Ibu'),

                                TextInput::make('nama_wali')
                                    ->label('Nama Wali'),
                                TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir'),

                                DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir'),

                                Select::make('jenis_kelamin')
                                    ->label('Jenis Kelamin')
                                    ->options([
                                        'laki-laki' => 'Laki-laki',
                                        'perempuan' => 'Perempuan',
                                    ]),

                                TextInput::make('nomor_telepon')
                                    ->label('Nomor Telepon'),

                                Textarea::make('alamat')
                                    ->label('Alamat Lengkap')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
