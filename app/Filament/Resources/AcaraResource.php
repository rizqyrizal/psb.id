<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcaraResource\Pages;
use App\Models\Acara;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class AcaraResource extends Resource
{
    protected static ?string $model = Acara::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Events';
    protected static ?string $navigationLabel = 'Acara';
        protected static ?int $navigationGroupSort = 3;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Acara')
                    ->description('Masukkan detail lengkap mengenai acara.')
                    ->columns(2) // Membagi section menjadi dua kolom
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Acara')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('tempat')
                            ->label('Tempat')
                            ->required()
                            ->maxLength(255),
                        DatePicker::make('waktu')
                            ->label('Tanggal Acara')
                            ->required(),
                        FileUpload::make('foto')
                            ->label('Foto Acara')
                            ->disk('public')
                            ->directory('acara-foto')
                            ->image()
                            ->imageEditor() // Tambahkan image editor untuk kemudahan
                            ->required(),
                        Textarea::make('desc')
                            ->label('Deskripsi')
                            ->required()
                            ->columnSpanFull(), // Membuat textarea menempati seluruh lebar
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto'),
                TextColumn::make('nama')
                    ->label('Nama Acara')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tempat')
                    ->label('Tempat')
                    ->searchable(),
                TextColumn::make('waktu')
                    ->label('Waktu')
                    ->date() // Format menjadi tanggal yang mudah dibaca
                    ->sortable(),
                TextColumn::make('desc')
                    ->label('Deskripsi')
                    ->limit(50) // Batasi jumlah karakter agar tidak terlalu panjang
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }), // Tambahkan tooltip untuk deskripsi lengkapnya
            ])
            ->filters([
                // Tambahkan filter untuk waktu acara
                Tables\Filters\Filter::make('waktu')
                    ->form([
                        DatePicker::make('waktu_mulai'),
                        DatePicker::make('waktu_selesai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['waktu_mulai'],
                                fn(Builder $query, $date): Builder => $query->whereDate('waktu', '>=', $date),
                            )
                            ->when(
                                $data['waktu_selesai'],
                                fn(Builder $query, $date): Builder => $query->whereDate('waktu', '<=', $date),
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
            'index' => Pages\ListAcaras::route('/'),
            'create' => Pages\CreateAcara::route('/create'),
            'edit' => Pages\EditAcara::route('/{record}/edit'),
        ];
    }
}
