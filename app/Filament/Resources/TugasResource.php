<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TugasResource\Pages;
use App\Filament\Resources\TugasResource\RelationManagers;
use App\Models\Tugas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;

use function Laravel\Prompts\select;

class TugasResource extends Resource
{
    protected static ?string $model = Tugas::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationLabel = 'Target Santri';

    protected static ?string $navigationGroup = 'Kelola Santri';

    protected static ?string $pluralLabel = 'Tugas';
    protected static ?int $navigationGroupSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Target')
                    ->description('Detail target yang harus diselesaikan santri')
                    ->schema([
                        Forms\Components\TextInput::make('target')
                            ->label('Nama Target')
                            ->placeholder('Contoh: Hafalan Juz 30')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('satuan')
                            ->label('Satuan')
                            ->options([
                                'jumlah juz' => 'Jumlah Juz',
                                'jumlah hadist' => 'Jumlah Hadist',
                                'lainnya' => 'Lainnya',
                            ])
                            ->native(false) // bikin dropdown lebih modern
                            ->required(),

                        Forms\Components\TextInput::make('jumlah_tugas')
                            ->label('Goals')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required()
                            ->suffix('target'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Deskripsi Tugas')
                    ->schema([
                        Forms\Components\RichEditor::make('deskripsi')
                            ->label('Deskripsi')
                            ->placeholder('Tuliskan detail instruksi tugas...')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                                'link',
                            ])
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Waktu')
                    ->description('Atur deadline tugas')
                    ->schema([
                        Forms\Components\DateTimePicker::make('waktu_berakhir')
                            ->label('Waktu Berakhir')
                            ->native(false) // tampilkan datetimepicker modern
                            ->minutesStep(15)
                            ->required(),
                    ])
                    ->columns(1),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('deskripsi')
                    ->label('desc')
                    ->hidden()
                    ->sortable(),
                TextColumn::make('target') // Display the 'nama' from the related 'kelas' table
                    ->label('Nama Target'),
                TextColumn::make('mapel.nama')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jumlah_tugas')
                    ->label('Jumlah Tugas')
                    ->sortable(),
                TextColumn::make('waktu_berakhir')
                    ->label('Waktu Berakhir')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Add any filters here if needed
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
            // Add any relation managers here if needed
        ];
    }
    public static function getGloballySearchableAttributes(): array
    {
        return [
            'target',
            'deskripsi',
            'satuan',
            'mapel.nama',
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTugas::route('/'),
            'create' => Pages\CreateTugas::route('/create'),
            'edit' => Pages\EditTugas::route('/{record}/edit'),
        ];
    }
}
