<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatatanResource\Pages;
use App\Filament\Resources\CatatanResource\RelationManagers;
use App\Models\Catatan;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\Tugas;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Collection;
use Filament\Forms\Get;

class CatatanResource extends Resource
{
    protected static ?string $model = Catatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil';
    protected static ?string $navigationLabel = 'Catatan';
    protected static ?string $navigationGroup = 'Santri';
    protected static ?string $pluralLabel = 'Catatan';
    protected static ?int $navigationGroupSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Bagian Kelas & Tugas
                Forms\Components\Section::make('Informasi Tugas')
                    ->description('Pilih kelas, tugas, dan santri yang terkait dengan catatan ini.')
                    ->collapsible() // bisa ditutup-buka
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Select::make('kelas_id')
                                    ->label('Kelas')
                                    ->relationship('kelas', 'nama')
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Select::make('tugas_id')
                                    ->label('Tugas')
                                    ->relationship('tugas', 'nama_tugas')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                            ]),

                        Select::make('santri_id')
                            ->label('Santri')
                            ->relationship('santri', 'nama')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                    ]),

                // Bagian Catatan
                Forms\Components\Section::make('Detail Catatan')
                    ->description('Isi detail tambahan tentang catatan tugas ini.')
                    ->schema([
                        TextInput::make('from')
                            ->label('Dari')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Guru Pembimbing'),

                        Radio::make('status')
                            ->label('Status')
                            ->options([
                                'Selesai' => 'Selesai',
                                'Belum Selesai' => 'Belum Selesai',
                                'Diverifikasi' => 'Diverifikasi',
                            ])
                            ->inline()
                            ->required(),

                        Textarea::make('desc')
                            ->label('Deskripsi')
                            ->rows(5)
                            ->placeholder('Masukkan deskripsi catatan di sini...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tugas.nama_tugas')
                    ->label('Tugas')
                    ->searchable()
                    ->sortable()
                    ->description(fn(Catatan $record): string => $record->tugas->guru->nama ?? 'Tidak Ada Guru'),

                TextColumn::make('santri.nama')
                    ->label('Santri')
                    ->searchable()
                    ->sortable()
                    ->description(fn(Catatan $record): string => $record->from),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'Selesai' => 'success',
                        'Belum Selesai' => 'warning',
                        'Diverifikasi' => 'primary',
                    ])
                    ->icon(fn(string $state): string => match ($state) {
                        'Selesai' => 'heroicon-o-check-circle',
                        'Belum Selesai' => 'heroicon-o-exclamation-circle',
                        'Diverifikasi' => 'heroicon-o-shield-check',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('desc')
                    ->label('Deskripsi')
                    ->words(10)
                    ->tooltip(fn($record): string => $record->desc)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('kelas_id')
                    ->label('Filter Kelas')
                    ->relationship('kelas', 'nama'),

                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'Selesai' => 'Selesai',
                        'Belum Selesai' => 'Belum Selesai',
                        'Diverifikasi' => 'Diverifikasi',
                    ]),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCatatans::route('/'),
            'create' => Pages\CreateCatatan::route('/create'),
            'edit' => Pages\EditCatatan::route('/{record}/edit'),
        ];
    }
}
