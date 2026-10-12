<?php

namespace App\Filament\Widgets;

use App\Models\Bill;
use App\Models\Classroom;
use App\Models\ElementaryStudent;
use App\Models\TransaksiPembayaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        $tagihan = Bill::all()->sum('nominal');
        $pembayaran = TransaksiPembayaran::all()->sum('nominal');
        $total_tunggakan = max(0, $tagihan - $pembayaran);
        $pembayaran_bulan_ini = TransaksiPembayaran::query()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('nominal');

        return [
            Stat::make('Siswa Aktif', ElementaryStudent::where('status', 'aktif')->count())
                ->description('Total siswa terdaftar aktif')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Total Kelas', Classroom::count())
                ->description('Jumlah rombongan belajar')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('info'),

            Stat::make('Tagihan Siswa', 'Rp ' . number_format($total_tunggakan, 0, ',', '.')),
                // ->description('Rp ' . number_format($total_tunggakan, 0, ',', '.'))
                // ->descriptionIcon('heroicon-m-exclamation-circle')
                // ->color('danger'),

            Stat::make('Terkumpul Bulan Ini', 'Rp ' . number_format($pembayaran_bulan_ini , 0, ',', '.'))
                // ->description(now()->translatedFormat('F Y'))
                // ->descriptionIcon('heroicon-m-banknotes')
                // ->color('success'),
        ];
    }
}
