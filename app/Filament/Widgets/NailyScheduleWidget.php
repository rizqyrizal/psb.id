<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Jadwal;
use Carbon\Carbon; // Import Carbon untuk mendapatkan hari ini

class NailyScheduleWidget extends Widget
{
    protected static string $view = 'filament.widgets.naily-schedule-widget';
    protected int | string | array $columnSpan = '2';
    public ?string $heading = 'Jadwal Hari Ini';

    public $schedules;

    public function mount(): void
    {
        $today = Carbon::now()->isoFormat('dddd');
        
        $this->schedules = Jadwal::with(['kelas', 'mapel'])
                                ->whereHas('kelas')
                                ->where('hari', $today)
                                ->get()
                                ->groupBy('kelas.nama'); // Kelompokkan berdasarkan nama kelas
    }
}