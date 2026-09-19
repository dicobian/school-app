<?php

namespace App\Filament\Widgets;

use App\Models\Bill;
use App\Models\Classroom;
use App\Models\ElementaryStudent;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        $tagihanBelumLunas = Bill::where('status', 'belum_lunas');
        $terkumpulBulanIni = Bill::where('status', 'lunas')
            ->whereMonth('tanggal_bayar', now()->month)
            ->whereYear('tanggal_bayar', now()->year)
            ->sum('nominal');

        return [
            Stat::make('Siswa Aktif', ElementaryStudent::where('status', 'Aktif')->count())
                ->description('Total siswa terdaftar aktif')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Total Kelas', Classroom::count())
                ->description('Jumlah rombongan belajar')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('info'),

            Stat::make('Tagihan Belum Lunas', $tagihanBelumLunas->count())
                ->description('Rp ' . number_format($tagihanBelumLunas->sum('nominal'), 0, ',', '.'))
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),

            Stat::make('Terkumpul Bulan Ini', 'Rp ' . number_format($terkumpulBulanIni, 0, ',', '.'))
                ->description(now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
