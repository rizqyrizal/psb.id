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

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Filament Shield';
    protected static ?string $navigationLabel = 'User';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Name selalu ada
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            // Email tetap muncul, required kalau bukan guru
            Forms\Components\TextInput::make('email')
                ->email()
                ->unique(ignoreRecord: true)
                ->required(fn (Get $get) => $get('roles') !== 'guru'),

            Forms\Components\Select::make('current_team_id')
                ->label('Team')
                ->relationship('currentTeam', 'name')
                ->searchable()
                ->preload()
                ->nullable(),

            Forms\Components\FileUpload::make('profile_photo_path')
                ->label('Foto Profil')
                ->image()
                ->disk('public')
                ->directory('profile-photos')
                ->avatar(),

            // Roles single value
            Forms\Components\Select::make('roles')
                ->label('Roles')
                ->options([
                    'guru' => 'Guru',
                    'user' => 'User',
                    'editor' => 'Editor',
                ])
                ->required()
                ->live()
                ->afterStateUpdated(fn (Set $set) => $set('password', null)),

            // Password tetap muncul, required kalau bukan guru
            Forms\Components\TextInput::make('password')
                ->password()
                ->maxLength(255)
                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (Get $get, string $operation) => $get('roles') !== 'guru' && $operation === 'create'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('profile_photo_path')
                ->label('Foto')
                ->circular(),

            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('email')
                ->searchable(),

            Tables\Columns\TextColumn::make('team.name')
                ->label('Team')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('roles')
                ->label('Roles')
                ->badge()
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime()
                ->sortable(),
        ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
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
