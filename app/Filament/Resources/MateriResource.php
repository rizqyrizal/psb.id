<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MateriResource\Pages;
use App\Models\Materi;
use App\Models\Mapel; // Pastikan Anda mengimpor model Mapel
use App\Models\Pembimbing;  // Pastikan Anda mengimpor model Guru
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MateriResource extends Resource
{
    protected static ?string $model = Materi::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationGroup = 'Kelola Pembimbing';
    protected static ?string $navigationLabel = 'Materi';
    protected static ?int $navigationGroupSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pembimbing')
                    ->description('Pilih pembimbing dan mata pelajaran terkait materi ini.')
                    ->icon('heroicon-o-user-circle')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Select::make('pembimbing_id')
                                    ->label('Pembimbing')
                                    ->placeholder('Pilih pembimbing')
                                    ->relationship('pembimbing', 'nama')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive(),

                                Select::make('mapel_id')
                                    ->label('Mata Pelajaran')
                                    ->placeholder('Pilih mata pelajaran')
                                    ->options(function (Forms\Get $get) {
                                        $pembimbingId = $get('pembimbing_id');
                                        if (!$pembimbingId) return [];

                                        $pembimbing = \App\Models\Pembimbing::find($pembimbingId);
                                        if (!$pembimbing || empty($pembimbing->mapel)) return [];

                                        // Jika mapel berupa array dari JSON
                                        if (is_array($pembimbing->mapel)) {
                                            return collect($pembimbing->mapel)
                                                ->mapWithKeys(fn($mapel) => [$mapel => $mapel])
                                                ->toArray();
                                        }

                                        // Jika hanya string
                                        return [$pembimbing->mapel => $pembimbing->mapel];
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),

                Forms\Components\Section::make('Detail Materi')
                    ->description('Isi detail tentang materi yang diajarkan.')
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->schema([
                        TextInput::make('nama')
                            ->label('Judul Materi')
                            ->placeholder('Contoh: Algoritma Dasar')
                            ->helperText('Gunakan judul yang jelas & singkat')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-book-open'),

                        Textarea::make('desc')
                            ->label('Deskripsi')
                            ->placeholder('Tuliskan ringkasan isi materi...')
                            ->helperText('Deskripsi maksimal 500 karakter')
                            ->rows(5)
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Judul Materi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pembimbing.nama')
                    ->label('Pembimbing')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pembimbing.mapel')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('desc')
                    ->label('Deskripsi')
                    ->words(10) // Hanya menampilkan 10 kata pertama
                    ->tooltip(fn($record): string => $record->desc) // Tampilkan deskripsi lengkap saat di-hover
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('pembimbing_id')
                    ->label('Filter Pembimbing')
                    ->relationship('pembimbing', 'nama'),
                Tables\Filters\SelectFilter::make('mapel_id')
                    ->label('Filter Mata Pelajaran')
                    ->relationship('mapel', 'nama'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getGloballySearchableAttributes(): array
    {
        return [
            'nama',
            'desc',
            'pembimbing.nama',
            'pembimbing.mapel',
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMateris::route('/'),
            'create' => Pages\CreateMateri::route('/create'),
            'edit' => Pages\EditMateri::route('/{record}/edit'),
        ];
    }
}
