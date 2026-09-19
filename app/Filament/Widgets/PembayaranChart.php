<?php

namespace App\Filament\Widgets;

use App\Models\Bill;
use Filament\Widgets\ChartWidget;

class PembayaranChart extends ChartWidget
{
    protected ?string $heading = 'Tren Pembayaran Tagihan (6 Bulan Terakhir)';

    protected function getData(): array
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->translatedFormat('M Y');

            $data[] = Bill::where('status', 'lunas')
                ->whereMonth('tanggal_bayar', $date->month)
                ->whereYear('tanggal_bayar', $date->year)
                ->sum('nominal');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Terkumpul (Rp)',
                    'data' => $data,
                    'backgroundColor' => '#22c55e',
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
