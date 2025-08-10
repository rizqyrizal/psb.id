<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalResource\Pages;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Pembimbing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JadwalResource extends Resource
{
    protected static ?string $model = Jadwal::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Jadwal';
    protected static ?string $pluralLabel = 'Jadwal';
    protected static ?string $navigationGroup = 'Kelola Pembimbing';
    protected static ?int $navigationGroupSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('pembimbing_id')
                    ->label('Pembimbing')
                    ->relationship('pembimbing', 'nama')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive(),
                Select::make('mapel')
                    ->label('Mata Pelajaran')
                    ->options(function (Forms\Get $get) {
                        $pembimbing = Pembimbing::find($get('pembimbing_id'));
                        return $pembimbing ? [$pembimbing->mapel => $pembimbing->mapel] : [];
                    })
                    ->required(),
                Select::make('hari')
                    ->label('Hari')
                    ->options([
                        'Monday'    => 'Senin',
                        'Tuesday'   => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday'  => 'Kamis',
                        'Friday'    => 'Jumat',
                        'Saturday'  => 'Sabtu',
                        'Sunday'    => 'Minggu',
                    ])
                    ->required(),
                TimePicker::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->seconds(false)
                    ->required(),
                TimePicker::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->seconds(false)
                    ->required(),
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
                TextColumn::make('hari')
                    ->label('Hari')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pembimbing.nama')
                    ->label('Pembimbing')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mapel')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jam_mulai')
                    ->label('Jam Mulai'),
                TextColumn::make('jam_selesai')
                    ->label('Jam Selesai'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kelas_id')
                    ->label('Filter Kelas')
                    ->relationship('kelas', 'nama'),
                Tables\Filters\SelectFilter::make('pembimbing_id')
                    ->label('Filter Pembimbing')
                    ->relationship('pembimbing', 'nama'),
                Tables\Filters\SelectFilter::make('hari')
                    ->label('Filter Hari')
                    ->options([
                        'Monday'    => 'Senin',
                        'Tuesday'   => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday'  => 'Kamis',
                        'Friday'    => 'Jumat',
                        'Saturday'  => 'Sabtu',
                        'Sunday'    => 'Minggu',
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
            ->defaultSort('hari');
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
            'index' => Pages\ListJadwals::route('/'),
            'create' => Pages\CreateJadwal::route('/create'),
            'edit' => Pages\EditJadwal::route('/{record}/edit'),
        ];
    }
}
