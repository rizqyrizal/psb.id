<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\LatestAcaraWidget;
use App\Filament\Widgets\NailyScheduleWidget;
use App\Filament\Widgets\GyStatsOverview;
use App\Filament\Widgets\MlassStudentCountWidget;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            GyStatsOverview::class, // Biasanya statistik di bagian atas
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            AccountWidget::class,
            FilamentInfoWidget::class,
            LatestAcaraWidget::class,
            MlassStudentCountWidget::class,
            NailyScheduleWidget::class,
        ];
    }
}
