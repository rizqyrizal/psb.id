<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Santri;
use App\Models\Pembimbing;

class GyStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Mengambil data dari database
        $totalSantri = Santri::count();
        $totalSantriPutra = Santri::where('jenis_kelamin', 'laki-laki')->count();
        $totalSantriPutri = Santri::where('jenis_kelamin', 'perempuan')->count();
        $totalPembimbing = Pembimbing::count();

        return [
            Stat::make('Total Santri', $totalSantri)
                ->description('Jumlah keseluruhan santri')
                ->descriptionIcon('heroicon-o-users')
                ->color('success')
                ->chart([3, 4, 5, 6, 6, 7]),


            Stat::make('Santri Putra', $totalSantriPutra)
                ->description('Jumlah santri laki-laki')
                ->descriptionIcon('heroicon-o-user')
                ->color('info')
                ->chart([2, 3, 4, 6, 7, 9]),

            Stat::make('Santri Putri', $totalSantriPutri)
                ->description('Jumlah santri perempuan')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('warning')
                ->chart([1, 2, 4, 5, 6, 8]),


            Stat::make('Total Pembimbing', $totalPembimbing)
                ->description('Jumlah keseluruhan pembimbing')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('primary')
                ->chart([1, 2, 4, 5, 6, 8]),

        ];
    }
}
