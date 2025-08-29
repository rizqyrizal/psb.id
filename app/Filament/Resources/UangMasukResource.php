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
                            ->required()
                            ->prefixIcon('heroicon-o-user'),
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
                                    ->required()
                                    ->prefixIcon('heroicon-o-banknotes'),

                                Select::make('payment_method')
                                    ->label('Metode Pembayaran')
                                    ->options([
                                        'cash' => 'Tunai',
                                        'transfer' => 'Transfer Bank',
                                        'qris' => 'QRIS',
                                    ])
                                    ->native(false)
                                    ->required()
                                    ->prefixIcon('heroicon-o-credit-card'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                TextInput::make('id_transaksi')
                                    ->label('ID Transaksi')
                                    ->default('NA')
                                    ->placeholder('Otomatis atau manual')
                                    ->required()
                                    ->prefixIcon('heroicon-o-hashtag'),

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
                                    ->required()
                                    ->prefixIcon('heroicon-o-rectangle-stack'),
                            ]),

                        DatePicker::make('tanggal_masuk')
                            ->label('Tanggal Masuk')
                            ->default(now())
                            ->displayFormat('d/m/Y')
                            ->required()
                            ->prefixIcon('heroicon-o-calendar'),

                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->placeholder('Opsional: Tambahkan catatan tambahan...')
                            ->rows(3)
                            ->maxLength(255)
                            ->hintIcon('heroicon-o-pencil-square'),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('santri.nama')
                    ->label('Santri')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user'),

                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR', true)
                    ->sortable()
                    ->icon('heroicon-o-banknotes'),

                TextColumn::make('payment_method')
                    ->label('Metode')
                    ->sortable()
                    ->icon('heroicon-o-credit-card'),

                TextColumn::make('tipe_transaksi')
                    ->label('Tipe')
                    ->sortable()
                    ->icon('heroicon-o-rectangle-stack'),

                TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->date('d M Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar'),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30)
                    ->icon('heroicon-o-document-text'),
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

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'santri.nama',
            'id_transaksi',
            'tipe_transaksi',
            'keterangan',
        ];
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
