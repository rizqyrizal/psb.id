<?php

namespace App\Filament\Widgets;

use App\Models\Absensi;
use App\Models\UangMasuk;
use Filament\Tables;
use Filament\Widgets\TableWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class JumlahKeuanganDanAbsensi extends TableWidget
{
    public string $activeTable = 'uang_masuk';

    protected int|string|array $columnSpan = 'full';

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\Action::make('uangMasuk')
                ->label('Uang Masuk')
                ->color($this->activeTable === 'uang_masuk' ? 'success' : 'secondary')
                ->action(fn() => $this->activeTable = 'uang_masuk'),

            Tables\Actions\Action::make('absensi')
                ->label('Absensi')
                ->color($this->activeTable === 'absensi' ? 'success' : 'secondary')
                ->action(fn() => $this->activeTable = 'absensi'),
        ];
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        if ($this->activeTable === 'uang_masuk') {
            return UangMasuk::query();
        }

        return Absensi::query();
    }

    protected function getTableColumns(): array
    {
        if ($this->activeTable === 'absensi') {
            return [
                TextColumn::make('santri.nama')
                    ->label('Santri')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'Hadir' => 'success',
                        'Izin' => 'warning',
                        'Alpha' => 'danger',
                        default => 'secondary',
                    }),
            ];
        }

        return [
            TextColumn::make('santri.nama')
                ->label('Santri')
                ->searchable()
                ->sortable(),

            TextColumn::make('amount')
                ->label('Jumlah')
                ->money('IDR', true)
                ->sortable(),

            TextColumn::make('payment_method')
                ->label('Metode Pembayaran')
                ->sortable(),

            TextColumn::make('tanggal_masuk')
                ->label('Tanggal Masuk')
                ->date('d M Y')
                ->sortable(),

            TextColumn::make('keterangan')
                ->label('Keterangan')
                ->limit(30),
        ];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return true;
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return $this->activeTable === 'uang_masuk'
            ? 'tanggal_masuk'
            : 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }
}
