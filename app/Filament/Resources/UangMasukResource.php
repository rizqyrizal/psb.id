<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UangMasukResource\Pages;
use App\Models\UangMasuk;
use App\Models\Santri;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Collection;
use Filament\Forms\Get;

class UangMasukResource extends Resource
{
    protected static ?string $model = UangMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Uang Masuk';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Informasi Santri')
                ->description('Pilih santri yang menerima atau melakukan pembayaran.')
                ->schema([
                    Select::make('santri_id')
                        ->label('Santri')
                        ->options(Santri::query()->pluck('nama', 'id'))
                        ->searchable()
                        ->placeholder('Pilih Santri')
                        ->required(),
                ])
                ->columns(1),

            Forms\Components\Section::make('Detail Transaksi')
                ->description('Isi informasi pembayaran secara lengkap.')
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            TextInput::make('amount')
                                ->label('Jumlah Pembayaran')
                                ->numeric()
                                ->prefix('Rp')
                                ->placeholder('Contoh: 500000')
                                ->required(),

                            Select::make('payment_method')
                                ->label('Metode Pembayaran')
                                ->options([
                                    'cash' => 'Tunai',
                                    'transfer' => 'Transfer Bank',
                                    'qris' => 'QRIS',
                                ])
                                ->native(false)
                                ->required(),
                        ]),

                    Forms\Components\Grid::make(2)
                        ->schema([
                            TextInput::make('id_transaksi')
                                ->label('ID Transaksi')
                                ->default('NA')
                                ->placeholder('Otomatis atau manual')
                                ->required(),

                            Select::make('tipe_transaksi')
                                ->label('Tipe Transaksi')
                                ->options([
                                    'SPP' => 'SPP',
                                    'Donasi' => 'Donasi',
                                    'Uang Saku' => 'Uang Saku',
                                    'infak_pendidikan' => 'Infak Pendidikan',
                                    'Lainnya' => 'Lainnya',
                                ])
                                ->native(false)
                                ->required(),
                        ]),

                    DatePicker::make('tanggal_masuk')
                        ->label('Tanggal Masuk')
                        ->default(now())
                        ->displayFormat('d/m/Y')
                        ->required(),

                    Textarea::make('keterangan')
                        ->label('Keterangan')
                        ->placeholder('Opsional: Tambahkan catatan tambahan...')
                        ->rows(3)
                        ->maxLength(255),
                ])
                ->columns(1)
                ->collapsible(), // Biar bisa di-expand/collapse
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('santri.nama')
                    ->label('Santri')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR', true)
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Metode')
                    ->sortable(),

                TextColumn::make('tipe_transaksi')
                    ->label('Tipe')
                    ->sortable(),

                TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30),
            ])
            ->filters([
                Filter::make('tanggal_masuk')
                    ->form([
                        DatePicker::make('from')->label('Dari'),
                        DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q, $date) => $q->whereDate('tanggal_masuk', '>=', $date))
                            ->when($data['until'], fn($q, $date) => $q->whereDate('tanggal_masuk', '<=', $date));
                    }),
            ])
            ->defaultSort('tanggal_masuk', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUangMasuks::route('/'),
            'create' => Pages\CreateUangMasuk::route('/create'),
            'edit' => Pages\EditUangMasuk::route('/{record}/edit'),
        ];
    }
}
