<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Acara;
use App\Models\Donasi;
use Illuminate\Support\Carbon;

class KegiatanWidget extends Widget
{
    public ?string $heading = 'Acara Terbaru';
    protected static string $view = 'filament.widgets.kegiatan';

    public $latestItems; // Bisa Acara atau Donasi
    public ?int $selectedId = null;
    public string $activeTab = 'acara'; // default tab

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        if ($this->activeTab === 'acara') {
            $this->heading = 'Acara Terbaru';
            $this->latestItems = Acara::latest('waktu')->limit(5)->get();
        } else {
            $this->heading = 'Donasi Terbaru';
            $this->latestItems = Donasi::latest('waktu')->limit(5)->get();
        }
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->selectedId = null; // reset detail
        $this->loadData();
    }

    public function showDescription(int $id): void
    {
        $this->selectedId = ($this->selectedId === $id) ? null : $id;
    }
}
