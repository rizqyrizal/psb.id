<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonasiResource\Pages;
use App\Models\Donasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// Import komponen form yang akan digunakan
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;

// Import komponen tabel yang akan digunakan
use Filament\Tables\Columns\TextColumn;

class DonasiResource extends Resource
{
    protected static ?string $model = Donasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar'; // Ganti ikon agar lebih sesuai
    protected static ?string $navigationLabel = 'Donasi';
    protected static ?string $navigationGroup = 'Events';
        protected static ?int $navigationGroupSort = 3;


    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Donasi')
                    ->description('Masukkan detail lengkap mengenai donasi.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Donasi')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('penerima')
                            ->label('Penerima Donasi')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        DatePicker::make('waktu')
                            ->label('Tanggal Donasi')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('desc')
                            ->label('Deskripsi Donasi')
                            ->required()
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Donasi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('penerima')
                    ->label('Penerima Donasi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('waktu')
                    ->label('Tanggal Donasi')
                    ->date()
                    ->sortable(),
                TextColumn::make('desc')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getLimit()) {
                            return null;
                        }
                        return $state;
                    }),
            ])
            ->filters([
                // Tambahkan filter untuk tanggal donasi
                Tables\Filters\Filter::make('waktu')
                    ->form([
                        DatePicker::make('tanggal_awal'),
                        DatePicker::make('tanggal_akhir'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal_awal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('waktu', '>=', $date),
                            )
                            ->when(
                                $data['tanggal_akhir'],
                                fn (Builder $query, $date): Builder => $query->whereDate('waktu', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonasis::route('/'),
            'create' => Pages\CreateDonasi::route('/create'),
            'edit' => Pages\EditDonasi::route('/{record}/edit'),
        ];
    }
}