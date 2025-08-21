<?php


namespace App\Filament\Resources;

use App\Filament\Resources\UserSettingResource\Pages;
use App\Models\UserSetting;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserSettingResource extends Resource
{
    protected static ?string $model = UserSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('theme')
                    ->options([
                        'light' => 'Terang',
                        'dark' => 'Gelap',
                    ])
                    ->default('light')
                    ->required(),
                Toggle::make('receive_notifications')
                    ->label('Terima Notifikasi')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Anda bisa menambahkan kolom di sini jika ingin melihat data
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
            'index' => Pages\ListUserSettings::route('/'),
            'create' => Pages\CreateUserSetting::route('/create'),
            'edit' => Pages\EditUserSetting::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}