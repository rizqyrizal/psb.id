<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsensiResource\Pages;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\Pembimbing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Absensi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('tipe_absensi')
                            ->label('Tipe Absensi')
                            ->options([
                                'santri' => 'Absensi Santri',
                                'pembimbing' => 'Absensi Pembimbing',
                            ])
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                if ($get('tipe_absensi') === 'pembimbing') {
                                    $pembimbing = Pembimbing::all()
                                        ->map(fn($p) => [
                                            'nama' => $p->nama,
                                            'status' => 'hadir',
                                            'jenis_kegiatan' => '',
                                            'keterangan' => '',
                                        ])
                                        ->toArray();
                                    $set('list_absensi', $pembimbing);
                                } else {
                                    $set('list_absensi', []); // reset jika santri, nanti diisi setelah kelas dipilih
                                }
                            }),

                        Forms\Components\Select::make('kelas_id')
                            ->label('Kelas')
                            ->options(Kelas::pluck('nama', 'id'))
                            ->reactive()
                            ->visible(fn(Get $get) => $get('tipe_absensi') === 'santri')
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                if ($get('kelas_id')) {
                                    $santri = Santri::where('kelas_id', $get('kelas_id'))
                                        ->with('kelas')
                                        ->get()
                                        ->map(fn($s) => [
                                            'nama' => $s->nama,
                                            'kelas' => $s->kelas->nama ?? '',
                                            'status' => 'hadir',
                                            'keterangan' => '',
                                        ])
                                        ->toArray();
                                    $set('list_absensi', $santri);
                                } else {
                                    $set('list_absensi', []);
                                }
                            }),

                        Forms\Components\Repeater::make('list_absensi')
                            ->label('Data Absensi')
                            ->schema(function (Get $get) {
                                if ($get('tipe_absensi') === 'santri') {
                                    return [
                                        Forms\Components\TextInput::make('nama')
                                            ->disabled()
                                            ->dehydrated(false),

                                        Forms\Components\TextInput::make('kelas')
                                            ->label('Kelas')
                                            ->disabled()
                                            ->dehydrated(false),

                                        Forms\Components\Fieldset::make('Status')
                                            ->schema([
                                                Forms\Components\Checkbox::make('hadir')
                                                    ->reactive()
                                                    ->afterStateUpdated(fn($state, Set $set) => $state ?
                                                        $set('ijin', false) & $set('sakit', false) & $set('alpa', false) : null),
                                                Forms\Components\Checkbox::make('ijin')
                                                    ->reactive()
                                                    ->afterStateUpdated(fn($state, Set $set) => $state ?
                                                        $set('hadir', false) & $set('sakit', false) & $set('alpa', false) : null),
                                                Forms\Components\Checkbox::make('sakit')
                                                    ->reactive()
                                                    ->afterStateUpdated(fn($state, Set $set) => $state ?
                                                        $set('hadir', false) & $set('ijin', false) & $set('alpa', false) : null),
                                                Forms\Components\Checkbox::make('alpa')
                                                    ->reactive()
                                                    ->afterStateUpdated(fn($state, Set $set) => $state ?
                                                        $set('hadir', false) & $set('ijin', false) & $set('sakit', false) : null),
                                            ]),

                                        Forms\Components\Textarea::make('keterangan')
                                            ->visible(fn(Get $get) => ($get('ijin') ?? false) || ($get('sakit') ?? false) || ($get('alpa') ?? false)),
                                    ];
                                }

                                if ($get('tipe_absensi') === 'pembimbing') {
                                    return [
                                        Forms\Components\TextInput::make('nama')
                                            ->disabled()
                                            ->dehydrated(false),

                                        Forms\Components\Fieldset::make('Status')
                                            ->schema([
                                                Forms\Components\Checkbox::make('hadir')
                                                    ->reactive()
                                                    ->afterStateUpdated(fn($state, Set $set) => $state ?
                                                        $set('ijin', false) & $set('sakit', false) & $set('alpa', false) : null),
                                                Forms\Components\Checkbox::make('ijin')
                                                    ->reactive()
                                                    ->afterStateUpdated(fn($state, Set $set) => $state ?
                                                        $set('hadir', false) & $set('sakit', false) & $set('alpa', false) : null),
                                                Forms\Components\Checkbox::make('sakit')
                                                    ->reactive()
                                                    ->afterStateUpdated(fn($state, Set $set) => $state ?
                                                        $set('hadir', false) & $set('ijin', false) & $set('alpa', false) : null),
                                                Forms\Components\Checkbox::make('alpa')
                                                    ->reactive()
                                                    ->afterStateUpdated(fn($state, Set $set) => $state ?
                                                        $set('hadir', false) & $set('ijin', false) & $set('sakit', false) : null),
                                            ]),

                                        Forms\Components\TextInput::make('jenis_kegiatan')
                                            ->label('Jenis Kegiatan'),

                                        Forms\Components\Textarea::make('keterangan')
                                            ->visible(fn(Get $get) => ($get('ijin') ?? false) || ($get('sakit') ?? false) || ($get('alpa') ?? false)),
                                    ];
                                }

                                return [];
                            })
                            ->defaultItems(0)
                            ->reactive(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tipe_absensi')
                    ->label('Tipe'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbsensis::route('/'),
            'create' => Pages\CreateAbsensi::route('/create'),
            'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }
}
