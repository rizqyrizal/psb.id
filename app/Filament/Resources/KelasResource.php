<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Models\Kelas;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Kelas';
    protected static ?string $navigationGroup = 'Kelola Pembimbing';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Informasi Kelas')
                    ->description('Lengkapi detail kelas, seperti nama, jenjang, dan jurusan.')
                    ->icon('heroicon-o-academic-cap')
                    ->collapsible()
                    ->schema([
                        \Filament\Forms\Components\Grid::make(2)
                            ->schema([
                                TextInput::make('nama')
                                    ->label('Nama Kelas')
                                    ->placeholder('Contoh: X RPL 1')
                                    ->helperText('Gunakan format standar penamaan kelas')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-identification'),

                                Select::make('jenjang')
                                    ->label('Jenjang')
                                    ->options([
                                        'SMK' => 'SMK',
                                        'SMP' => 'SMP',
                                        'slta' => 'SLTA',
                                        'sltp' => 'SLTP',
                                    ])
                                    ->required()
                                    ->native(false)
                                    ->helperText('Pilih tingkat pendidikan')
                                    ->prefixIcon('heroicon-o-building-office'),
                            ]),

                        TextInput::make('jurusan')
                            ->label('Jurusan')
                            ->placeholder('Contoh: Rekayasa Perangkat Lunak')
                            ->helperText('Isi sesuai dengan jurusan kelas')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-briefcase'),
                    ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenjang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jurusan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
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
        return [
            //
        ];
    }
    public static function getGloballySearchableAttributes(): array
    {
        return [
            'nama',
            'jenjang',
            'jurusan',
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }
}
