<?php

namespace App\Filament\Widgets;

use App\Models\Santri;
use App\Models\UangMasuk;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class IuranPondokDanJenjang extends BaseWidget
{
    protected function getCards(): array
    {
        // Hitung jumlah santri SMK
        $jumlahSmk = Santri::whereHas('kelas', function ($query) {
            $query->where('jenjang', 'SMK');
        })->count();

        // Hitung jumlah santri SMP
        $jumlahSmp = Santri::whereHas('kelas', function ($query) {
            $query->where('jenjang', 'SMP');
        })->count();

        // Hitung total uang masuk
        $totalUang = UangMasuk::sum('amount');

        return [
            Card::make('Santri SMK', $jumlahSmk)
                ->description('Total santri jenjang SMK')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success'),

            Card::make('Santri SMP', $jumlahSmp)
                ->description('Total santri jenjang SMP')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info'),

            Card::make('Total Uang Masuk', 'Rp ' . number_format($totalUang, 0, ',', '.'))
                ->description('Total pemasukan tercatat')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('primary'),
        ];
    }
}
