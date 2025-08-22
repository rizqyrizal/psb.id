<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\HasilSantri;
use App\Filament\Widgets\IuranPondokDanJenjang;
use App\Filament\Widgets\JumlahKeuanganDanAbsensi;
use App\Filament\Widgets\JumlahSantriPerKelas;
use App\Filament\Widgets\KegiatanWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            HasilSantri::class, // Biasanya statistik di bagian atas
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            AccountWidget::class,
            FilamentInfoWidget::class,
            KegiatanWidget::class,
            JumlahSantriPerKelas::class,
            IuranPondokDanJenjang::class,
            JumlahKeuanganDanAbsensi::class,
        ];
    }

}
