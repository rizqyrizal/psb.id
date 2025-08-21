<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SantriResource\Pages;
use App\Models\Santri;
use App\Models\Pembimbing;
use App\Models\Kelas;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// === Forms Components ===
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section as FormSection;
use Filament\Forms\Components\Grid as FormGrid;

// === Tables Components ===
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

// === Infolists Components ===
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Group;


class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Santri';

    protected static ?string $navigationLabel = 'Kelola Santri';

    // === FORM ===
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FormSection::make('Data Pribadi')
                    ->description('Informasi dasar siswa.')
                    ->schema([
                        TextInput::make('nama')
                            ->required()
                            ->maxLength(255),
                        FormGrid::make(3)
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
                        FormGrid::make(2)
                            ->schema([
                                TextInput::make('tempat_lahir')
                                    ->required()
                                    ->maxLength(255),
                                DatePicker::make('tanggal_lahir')
                                    ->required(),
                            ]),
                    ])->columns(1),

                FormSection::make('Informasi Kontak')
                    ->description('Detail kontak dan alamat siswa.')
                    ->schema([
                        Textarea::make('alamat')
                            ->required()
                            ->columnSpanFull(),
                        FormGrid::make(2)
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

                FormSection::make('Informasi Akademik')
                    ->description('Data terkait kelas dan wali kelas.')
                    ->schema([
                        FormGrid::make(2)
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

                FormSection::make('Foto Siswa')
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

    // === TABLE ===
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
            ->actions([
                Tables\Actions\ViewAction::make(), // ✅ tombol View
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                Grid::make(3)
                    ->schema([
                        Section::make('Data Pribadi')
                            ->description('Data santri yang telah dikelompokkan')
                                    ->schema([
                                        TextEntry::make('nama')
                                            ->weight('bold')
                                            ->columnSpanFull(),
                                        TextEntry::make('kelas.nama')
                                            ->label('Kelas')
                                            ->badge(),
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('nik')
                                            ->badge()
                                            ->color('success')
                                            ->weight('bold'),
                                        TextEntry::make('nis')
                                            ->badge()
                                            ->color('success')
                                            ->weight('bold'),
                                        TextEntry::make('nisn')
                                            ->badge()
                                            ->color('success')
                                            ->weight('bold'),
                                    ]),
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('alamat')
                                            ->markdown()
                                            ->prose()
                                            ->columnSpan(1),
                                        ImageEntry::make('foto')
                                            ->disk('public')
                                            ->square()
                                            ->label('Foto Santri'),
                                    ]),
                            ])->columnSpan(2),

                        Section::make('Informasi Tambahan')
                            ->schema([
                                TextEntry::make('no_telepon')
                                    ->icon('heroicon-o-phone')
                                    ->iconColor('warning')
                                    ->label('No. Telepon'),
                                TextEntry::make('jenis_kelamin')
                                    ->label('Jenis Kelamin')
                                    ->icon('heroicon-o-user-circle')
                                    ->iconColor('danger'),
                                TextEntry::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->icon('heroicon-o-map-pin')
                                    ->iconColor('info'),
                                TextEntry::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->icon('heroicon-o-tag')
                                    ->iconColor('success')
                                    ->date(),
                                TextEntry::make('email')
                                    ->icon('heroicon-o-envelope')
                                    ->iconColor('warning')
                                    ->label('Email'),
                            ])->columnSpan(1),
                    ]),

                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Akademik')
                            ->icon('heroicon-o-book-open')
                            ->schema([
                                Section::make('Informasi Akademik')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('pembimbing.nama')->label('Wali Kelas'),
                                            ]),
                                    ])
                                    ->columnSpanFull(), // ✅ ini bikin full
                            ]),
                        Tabs\Tab::make('Mapel')
                            ->icon('heroicon-o-book-open')
                            ->schema([
                                Section::make('Informasi Mapel')
                                    ->schema([
                                        TextEntry::make('pembimbing.mapel')->label('Mapel'),
                                    ])
                                    ->columnSpanFull(),
                            ]),
                        Tabs\Tab::make('Absensi')
                            ->icon('heroicon-o-book-open')
                            ->schema([
                                Section::make('Informasi Absensi')
                                    ->schema([
                                        TextEntry::make('absensi')->label('Absensi'),
                                    ])
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(), // ✅ biar tabnya juga full
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'nama',
            'nis',
            'nisn',
            'nik',
            'kelas.nama',
            'pembimbing.nama',
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSantris::route('/'),
            'create' => Pages\CreateSantri::route('/create'),
            'edit' => Pages\EditSantri::route('/{record}/edit'),
            'view' => Pages\ViewSantri::route('/{record}'), // ✅ halaman View
        ];
    }
}
