<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SantriResource\Pages;
use App\Filament\Resources\SantriResource\RelationManagers;
use App\Models\Santri;
use App\Models\Pembimbing;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
        protected static ?string $navigationGroup = 'Santri';

    protected static ?string $navigationLabel = ' kelola Santri';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Pribadi')
                    ->description('Informasi dasar siswa.')
                    ->schema([
                        TextInput::make('nama')
                            ->required()
                            ->maxLength(255),
                        Grid::make(3)
                            ->schema([
                                TextInput::make('nik')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('nis')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('nisn')
                                    ->required()
                                    ->maxLength(255),
                                Radio::make('jenis_kelamin')
                                    ->options([
                                        'laki-laki' => 'Laki-laki',
                                        'perempuan' => 'Perempuan',
                                        ])
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('tempat_lahir')
                                    ->required()
                                    ->maxLength(255),
                                DatePicker::make('tanggal_lahir')
                                    ->required(),
                            ]),
                    ])->columns(1),

                Section::make('Informasi Kontak')
                    ->description('Detail kontak dan alamat siswa.')
                    ->schema([
                        Textarea::make('alamat')
                            ->required()
                            ->columnSpanFull(),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('no_telepon')
                                    ->tel()
                                    ->nullable()
                                    ->maxLength(255)
                                    ->prefix('+62'),
                                TextInput::make('email')
                                    ->email()
                                    ->nullable()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                            ]),
                    ]),

                Section::make('Informasi Akademik')
                    ->description('Data terkait kelas dan wali kelas.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('kelas_id')
                                    ->label('Kelas')
                                    ->options(Kelas::all()->pluck('nama', 'id'))
                                    ->nullable()
                                    ->searchable(),
                                Select::make('wali_kelas_id')
                                    ->label('Wali Kelas')
                                    ->options(Pembimbing::all()->pluck('nama', 'id'))
                                    ->nullable()
                                    ->searchable(),
                            ]),
                    ]),

                Section::make('Foto Siswa')
                    ->description('Unggah foto profil siswa.')
                    ->schema([
                        FileUpload::make('foto')
                            ->disk('public')
                            ->directory('siswa-foto')
                            ->image()
                            ->nullable()
                            ->columnSpanFull(),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nis')
                    ->searchable(),
                TextColumn::make('nisn')
                    ->searchable(),
                TextColumn::make('jenis_kelamin'),
                TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('pembimbing.nama')
                    ->label('Wali Kelas')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('foto')
                    ->square(),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSantris::route('/'),
            'create' => Pages\CreateSantri::route('/create'),
            'edit' => Pages\EditSantri::route('/{record}/edit'),
        ];
    }
}
