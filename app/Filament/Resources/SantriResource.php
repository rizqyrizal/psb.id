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
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section as FormSection;
use Filament\Forms\Components\Grid as FormGrid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Tabs;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Kelola Santri';

    protected static ?string $navigationLabel = ' Santri';

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
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-user'),
                        FormGrid::make(3)
                            ->schema([
                                TextInput::make('nik')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-identification'),
                                TextInput::make('nis')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-hashtag'),
                                TextInput::make('nisn')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-hashtag'),
                                Radio::make('jenis_kelamin')
                                    ->options([
                                        'laki-laki' => 'Laki-laki',
                                        'perempuan' => 'Perempuan',
                                    ])
                                    ->required()
                                    ->inline()
                                    ->label('Jenis Kelamin'),
                            ]),
                        FormGrid::make(2)
                            ->schema([
                                TextInput::make('tempat_lahir')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-map-pin'),
                                DatePicker::make('tanggal_lahir')
                                    ->required()
                                    ->prefixIcon('heroicon-o-calendar'),
                            ]),
                    ])->columns(1),

                FormSection::make('Informasi Kontak')
                    ->description('Detail kontak dan alamat siswa.')
                    ->schema([
                        Textarea::make('alamat')
                            ->required()
                            ->columnSpanFull()
                            ->hintIcon('heroicon-o-home'),
                        FormGrid::make(2)
                            ->schema([
                                TextInput::make('no_telepon')
                                    ->tel()
                                    ->nullable()
                                    ->maxLength(255)
                                    ->prefix('+62')
                                    ->prefixIcon('heroicon-o-phone'),
                                TextInput::make('email')
                                    ->email()
                                    ->nullable()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-envelope'),
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
                                    ->searchable()
                                    ->prefixIcon('heroicon-o-academic-cap'),
                                Select::make('wali_kelas_id')
                                    ->label('Wali Kelas')
                                    ->options(Pembimbing::all()->pluck('nama', 'id'))
                                    ->nullable()
                                    ->searchable()
                                    ->prefixIcon('heroicon-o-user-circle'),
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
                            ->columnSpanFull()
                            ->avatar()
                            ->label('Foto Santri'),
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
                    ->sortable()
                    ->icon('heroicon-o-user'),
                TextColumn::make('nis')
                    ->searchable()
                    ->icon('heroicon-o-hashtag'),
                TextColumn::make('nisn')
                    ->searchable()
                    ->icon('heroicon-o-hashtag'),
                TextColumn::make('jenis_kelamin')
                    ->icon('heroicon-o-user-circle'),
                TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-o-academic-cap'),
                TextColumn::make('pembimbing.nama')
                    ->label('Wali Kelas')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-o-user-group'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-o-calendar'),
                ImageColumn::make('foto')
                    ->square()
                    ->label('Foto'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->icon('heroicon-o-eye'),
                Tables\Actions\EditAction::make()->icon('heroicon-o-pencil'),

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
                                    ->icon('heroicon-o-user')
                                    ->columnSpanFull(),
                                TextEntry::make('kelas.nama')
                                    ->label('Kelas')
                                    ->badge()
                                    ->icon('heroicon-o-academic-cap'),
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('nik')
                                            ->badge()
                                            ->color('success')
                                            ->weight('bold')
                                            ->icon('heroicon-o-identification'),
                                        TextEntry::make('nis')
                                            ->badge()
                                            ->color('success')
                                            ->weight('bold')
                                            ->icon('heroicon-o-hashtag'),
                                        TextEntry::make('nisn')
                                            ->badge()
                                            ->color('success')
                                            ->weight('bold')
                                            ->icon('heroicon-o-hashtag'),
                                    ]),
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('alamat')
                                            ->markdown()
                                            ->prose()
                                            ->columnSpan(1)
                                            ->icon('heroicon-o-home'),
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
                                    ->icon('heroicon-o-calendar')
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
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                Section::make('Informasi Akademik')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('pembimbing.nama')
                                                    ->label('Wali Kelas')
                                                    ->icon('heroicon-o-user-group'),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                            ]),
                        Tabs\Tab::make('Mapel')
                            ->icon('heroicon-o-book-open')
                            ->schema([
                                Section::make('Informasi Mapel')
                                    ->schema([
                                        TextEntry::make('pembimbing.mapel')
                                            ->label('Mapel')
                                            ->icon('heroicon-o-book-open'),
                                    ])
                                    ->columnSpanFull(),
                            ]),
                        Tabs\Tab::make('Absensi')
                            ->icon('heroicon-o-clipboard-document-check')
                            ->schema([
                                Section::make('Informasi Absensi')
                                    ->schema([
                                        TextEntry::make('absensi')
                                            ->label('Absensi')
                                            ->icon('heroicon-o-clipboard-document-check'),
                                    ])
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
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
            'view' => Pages\ViewSantri::route('/{record}'),
        ];
    }
}
