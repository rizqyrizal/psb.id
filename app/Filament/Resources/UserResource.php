<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Columns\TextInputColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Filament Shield';
    protected static ?string $navigationLabel = 'User';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi User')
                ->description('Lengkapi detail akun user berikut.')
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->unique(ignoreRecord: true)
                                ->required(fn(Get $get) => ! in_array('guru', (array) $get('roles'))),
                        ]),
                    Forms\Components\TextInput::make('current_team_id')
                        ->label('Team ID')
                        ->numeric()
                        ->exists('teams', 'id')
                        ->nullable()
                        ->helperText('Masukkan ID tim yang sudah ada di database. Kosongkan jika tidak perlu.'),
                ]),
            Forms\Components\Section::make('Hak Akses & Keamanan')
                ->collapsible()
                ->schema([
                    Forms\Components\Select::make('roles')
                        ->label('Roles')
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn(Set $set) => $set('password', null)),
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->revealable()
                        ->maxLength(255)
                        ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                        ->dehydrated(fn($state) => filled($state))
                        ->required(fn(Get $get, string $operation) => ! in_array('guru', (array) $get('roles')) && $operation === 'create'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextInputColumn::make('current_team_id')
                    ->label('Team ID')
                    ->placeholder('N/A')
                    ->rules(['nullable', 'numeric', 'exists:teams,id']),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => is_array($state) ? implode(', ', $state) : $state),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}