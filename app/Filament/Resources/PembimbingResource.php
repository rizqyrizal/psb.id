<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembimbingResource\Pages;
use App\Models\Pembimbing;
use App\Models\Kelas;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Components\Tabs;


class PembimbingResource extends Resource
{
    protected static ?string $model = Pembimbing::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Kelola Pembimbing';
    protected static ?string $navigationLabel = 'Pembimbing';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Pembimbing Form')
                    ->tabs([
                        // ================= TAB 1 =================
                        Tabs\Tab::make('Data Pribadi')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Section::make('Informasi Pribadi')
                                    ->description('Masukkan data diri lengkap guru.')
                                    ->schema([
                                        TextInput::make('nama')
                                            ->label('Nama Lengkap')
                                            ->placeholder('Contoh: Budi Santoso')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        Grid::make(3)->schema([
                                            TextInput::make('nip')
                                                ->label('NIP')
                                                ->required()
                                                ->maxLength(255),

                                            TextInput::make('nuptk')
                                                ->label('NUPTK')
                                                ->required()
                                                ->maxLength(255),

                                            TextInput::make('tempat_lahir')
                                                ->label('Tempat Lahir')
                                                ->required()
                                                ->maxLength(255),
                                        ]),

                                        Grid::make(3)->schema([
                                            DatePicker::make('tanggal_lahir')
                                                ->label('Tanggal Lahir')
                                                ->required(),

                                            Radio::make('jenis_kelamin')
                                                ->label('Jenis Kelamin')
                                                ->options([
                                                    'laki-laki' => 'Laki-laki',
                                                    'perempuan' => 'Perempuan',
                                                ])
                                                ->required(),

                                            TextInput::make('no_telepon')
                                                ->label('Nomor Telepon')
                                                ->tel()
                                                ->prefix('+62')
                                                ->placeholder('81234567890')
                                                ->maxLength(255),
                                        ]),

                                        TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->placeholder('contoh@email.com')
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        // ================= TAB 2 =================
                        Tabs\Tab::make('Tampilan & Wali Kelas')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Section::make('Pengaturan Tampilan')
                                    ->schema([
                                        Toggle::make('is_published')
                                            ->label('Tampilkan di pengajar')
                                            ->live(),

                                        Toggle::make('is_featured')
                                            ->label('Tandai sebagai Wali Kelas')
                                            ->live(),

                                        FileUpload::make('foto')
                                            ->label('Foto Guru')
                                            ->disk('public')
                                            ->directory('guru-photos')
                                            ->avatar(),

                                        Select::make('kelas_id')
                                            ->label('Wali Kelas')
                                            ->relationship('kelas', 'nama')
                                            ->searchable()
                                            ->required(fn(Get $get) => $get('is_featured') === true)
                                            ->visible(fn(Get $get) => $get('is_featured') === true),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        // ================= TAB 3 =================
                        Tabs\Tab::make('Kontak & Tambahan')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Section::make('Informasi Kontak & Tambahan')
                                    ->description('Alamat, mata pelajaran, dan status guru.')
                                    ->schema([
                                        Textarea::make('alamat')
                                            ->label('Alamat Lengkap')
                                            ->required()
                                            ->columnSpanFull(),

                                        Grid::make(2)->schema([
                                            Select::make('wali_kelas_type')
                                                ->label('Tipe Kelas')
                                                ->options([
                                                    'kelas' => 'Kelas',
                                                    'mustawa' => 'Mustawa',
                                                    'halaqoh' => 'Halaqoh',
                                                ])
                                                ->live()
                                                ->nullable()
                                                ->visible(fn(Get $get): bool => $get('is_published') === true),

                                            Select::make('mapel')
                                                ->label('Mata Pelajaran')
                                                ->required()
                                                ->searchable()
                                                ->multiple()
                                                ->options(function (Get $get) {
                                                    $waliKelasType = $get('wali_kelas_type');

                                                    if ($waliKelasType === 'kelas') {
                                                        return [
                                                            'Pendidikan Agama dan Budi Pekerti' => 'Pendidikan Agama dan Budi Pekerti',
                                                            'Pendidikan Pancasila' => 'Pendidikan Pancasila',
                                                            'Bahasa Indonesia' => 'Bahasa Indonesia',
                                                            'Matematika' => 'Matematika',
                                                            'Ilmu Pengetahuan Alam (IPA)' => 'Ilmu Pengetahuan Alam (IPA)',
                                                            'Ilmu Pengetahuan Sosial (IPS)' => 'Ilmu Pengetahuan Sosial (IPS)',
                                                            'Bahasa Inggris' => 'Bahasa Inggris',
                                                            'Seni Budaya' => 'Seni Budaya',
                                                            'Pendidikan Jasmani, Olahraga, dan Kesehatan (PJOK)' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan (PJOK)',
                                                            'Prakarya' => 'Prakarya',
                                                            'Informatika' => 'Informatika',
                                                            'Bahasa Daerah' => 'Bahasa Daerah',
                                                        ];
                                                    } elseif ($waliKelasType === 'mustawa') {
                                                        return [
                                                            'Akidah' => 'Akidah',
                                                            'Fikih' => 'Fikih',
                                                            'Tafsir' => 'Tafsir',
                                                            'Hadis' => 'Hadis',
                                                            'Bahasa Arab' => 'Bahasa Arab',
                                                            'Tahsin' => 'Tahsin',
                                                        ];
                                                    } elseif ($waliKelasType === 'halaqoh') {
                                                        return [
                                                            'Tahfidz Al-Qur\'an' => 'Tahfidz Al-Qur\'an',
                                                            'Hadis' => 'Hadis',
                                                            'Bahasa Arab' => 'Bahasa Arab',
                                                            'Fikih' => 'Fikih',
                                                        ];
                                                    }

                                                    return [];
                                                }),
                                        ]),
                                    ])
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->square(),
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nip')
                    ->searchable(),
                TextColumn::make('nuptk')
                    ->searchable(),
                TextColumn::make('jenis_kelamin'),
                IconColumn::make('is_featured')
                    ->label('Wali Kelas?')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('kelas.nama')
                    ->label('Wali Kelas dari')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('mapel')
                    ->label('Mata Pelajaran')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kelas')
                    ->label('Wali Kelas')
                    ->relationship('kelas', 'nama')
                    ->multiple(),
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
            'nip',
            'nuptk',
            'email',
            'no_telepon',
            'kelas.nama',
            'mapel',
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembimbings::route('/'),
            'create' => Pages\CreatePembimbing::route('/create'),
            'edit' => Pages\EditPembimbing::route('/{record}/edit'),
        ];
    }
}
