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
                Grid::make(3)
                    ->schema([
                        Section::make('Informasi Pribadi')
                            ->description('Masukkan data diri lengkap guru.')
                            ->schema([
                                TextInput::make('nama')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('nip')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('nuptk')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('tempat_lahir')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
                                Grid::make(3)
                                    ->schema([
                                        DatePicker::make('tanggal_lahir')
                                            ->required(),
                                        Radio::make('jenis_kelamin')
                                            ->options([
                                                'laki-laki' => 'Laki-laki',
                                                'perempuan' => 'Perempuan',
                                            ])
                                            ->required(),
                                        TextInput::make('no_telepon')
                                            ->tel()
                                            ->prefix('+62')
                                            ->nullable()
                                            ->maxLength(255),
                                    ]),
                                TextInput::make('email')
                                    ->email()
                                    ->nullable()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                            ])->columnSpan(2),
                        Section::make('Opsi Tampilan')
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        Toggle::make('is_published')
                                            ->label('Tampilkan di pengajar')
                                            ->live(),
                                        Toggle::make('is_featured')
                                            ->label('Tandai sebagai Wali Kelas')
                                            ->live(),

                                        // Tambahkan field untuk foto
                                        FileUpload::make('foto')
                                            ->disk('public') // Sesuaikan disk storage Anda
                                            ->directory('guru-photos') // Folder untuk menyimpan foto
                                            ->avatar(),

                                        Select::make('kelas_id')
                                            ->label('Wali Kelas')
                                            ->relationship('kelas', 'nama')
                                            ->searchable()
                                            ->required(fn(Get $get) => $get('is_featured') === true)
                                            ->visible(fn(Get $get) => $get('is_featured') === true),
                                    ])
                            ])->columnSpan(1),
                    ]),
                Section::make('Informasi Kontak dan Tambahan')
                    ->description('Alamat, mata pelajaran, dan status guru.')
                    ->schema([
                        Textarea::make('alamat')
                            ->required()
                            ->columnSpanFull(),
                        Grid::make(2)
                            ->schema([
                                Select::make('wali_kelas_type')
                                    ->label('Tipe Kelas')
                                    ->options([
                                        'kelas' => 'Kelas',
                                        'mustawa' => 'Mustawa',
                                        'halaqoh' => 'Halaqoh',
                                    ])
                                    ->live()
                                    ->nullable()
                                    ->visible(fn(\Filament\Forms\Get $get): bool => $get('is_published') === true),

                                Select::make('mapel')
                                    ->label('Mata Pelajaran')
                                    ->required()
                                    ->searchable()
                                    ->multiple() 
                                    ->options(function (\Filament\Forms\Get $get) {
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
                    ]),
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
