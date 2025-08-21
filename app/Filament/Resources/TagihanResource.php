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

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Tagihan';
    protected static ?string $modelLabel = 'Tagihan';
        protected static ?string $navigationGroup = 'Santri';

    protected static ?string $pluralModelLabel = 'Daftar Tagihan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('santri_id')
                    ->label('Santri')
                    ->options(\App\Models\Santri::pluck('nama', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('keterangan')
                    ->label('Keterangan')
                    ->placeholder('Opsional')
                    ->maxLength(255)
                    ->nullable(),

                Forms\Components\DatePicker::make('jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Forms\Components\Toggle::make('lunas')
                    ->label('Lunas')
                    ->default(false),
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
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
