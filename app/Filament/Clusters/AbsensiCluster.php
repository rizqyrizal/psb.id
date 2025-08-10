<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class AbsensiCluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Absensi Siswa';
    protected static ?string $model = \App\Models\Absensi::class; // Opsional, bisa dihapus
    public static function getNavigationLabel(): string
    {
        return 'Absensi';
    }

    public static function getNavigationSort(): ?int
    {
        return 1; // Atur urutan navigasi
    }
}