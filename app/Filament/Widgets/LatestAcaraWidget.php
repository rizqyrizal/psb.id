<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Acara;
use Illuminate\Support\Carbon; // Import Carbon untuk tanggal

class LatestAcaraWidget extends Widget
{
    public ?string $heading = 'Acara Terbaru';
    protected static string $view = 'filament.widgets.latest-acara-widget';

    public $latestAcaras;
    public ?int $selectedAcaraId = null; // Properti baru untuk melacak acara yang dipilih

    public function mount(): void
    {
        $this->latestAcaras = Acara::latest('waktu')->limit(5)->get();
    }

    // Metode baru yang dipanggil saat gambar diklik
    public function showDescription(int $acaraId): void
    {
        // Toggle deskripsi: jika sudah dipilih, tutup. Jika belum, buka.
        $this->selectedAcaraId = ($this->selectedAcaraId === $acaraId) ? null : $acaraId;
    }
}
