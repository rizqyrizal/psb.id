<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Santri; // Sesuaikan dengan namespace model Siswa Anda
use App\Models\Kelas; // Sesuaikan dengan namespace model Kelas Anda

class JumlahSantriPerKelas extends Widget
{
    protected static string $view = 'filament.widgets.jumlah-santri-perkelas';
    protected int | string | array $columnSpan = 1;
    public ?string $heading = 'Jumlah Santri per kelas';

    public $classCounts;

    public function mount(): void
    {
        $this->classCounts = Santri::select('kelas_id')
            ->selectRaw('count(*) as total')
            ->with('kelas')
            ->groupBy('kelas_id')
            ->get();
    }
}