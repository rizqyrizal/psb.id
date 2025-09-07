<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagihanResource\Pages;
use App\Models\Tagihan;
use App\Models\Santri;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;


class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Tagihan';
    protected static ?string $modelLabel = 'Tagihan';
    protected static ?string $navigationGroup = 'Kelola Santri';

    protected static ?string $pluralModelLabel = 'Daftar Tagihan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Tagihan')
                    ->description('Lengkapi data tagihan santri dengan benar.')
                    ->schema([

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('santri_id')
                                    ->label('Santri')
                                    ->options(Santri::all()->pluck('nama', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->hint('Pilih santri yang akan dikenakan tagihan')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('keterangan')
                                    ->label('Keterangan')
                                    ->placeholder('Contoh: Uang SPP Bulan Agustus')
                                    ->maxLength(255)
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('jatuh_tempo')
                                    ->label('Jatuh Tempo')
                                    ->required()
                                    ->native(false) // tampilkan datepicker modern
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('amount')
                                    ->label('Jumlah Tagihan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->live(onBlur: true) // biar auto format pas keluar input
                                    ->rule('integer')    // biar validasi angka bulat
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('clear')
                                            ->icon('heroicon-o-x-mark')
                                            ->action(fn($state, callable $set) => $set('amount', null))
                                    ),

                            ]),
                    ])
                    ->columns(1)
                    ->collapsible(),

                Forms\Components\Section::make('Status Pembayaran')
                    ->description('Atur status pembayaran tagihan.')
                    ->schema([
                        Forms\Components\Toggle::make('lunas')
                            ->label('Sudah Lunas?')
                            ->onColor('success')
                            ->offColor('danger')
                            ->default(false)
                            ->helperText('Tandai jika tagihan ini sudah dibayar.'),
                    ])
                    ->collapsible(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('santri.nama')
                    ->label('Santri')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30)
                    ->wrap(),

                Tables\Columns\TextColumn::make('jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\IconColumn::make('lunas')
                    ->label('Lunas')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('lunas')
                    ->label('Status Lunas'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export')
                    ->exports([
                        ExcelExport::make()->fromTable(),
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->label('Export Data')
                        ->exports([
                            ExcelExport::make()->fromTable(),
                        ]),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Tambah relasi ke Santri jika perlu
        ];
    }
    public static function getGloballySearchableAttributes(): array
    {
        return [
            'santri.nama',
            'keterangan',
            'amount',
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTagihans::route('/'),
            'create' => Pages\CreateTagihan::route('/create'),
            'edit' => Pages\EditTagihan::route('/{record}/edit'),
        ];
    }
}
