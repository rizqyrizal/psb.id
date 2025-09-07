<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use App\Models\Team;
use Illuminate\Support\Facades\Auth; // Tambahkan import ini

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('createTeam')
                ->label('Buat Tim')
                ->icon('heroicon-o-plus')
                ->form([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Tim')
                        ->required(),
                    Forms\Components\Hidden::make('user_id')
                        ->default(Auth::id()),
                ])
                ->action(function (array $data) {
                    Team::create($data);
                })
                ->slideOver(),
            
            Actions\CreateAction::make(),
        ];
    }
}