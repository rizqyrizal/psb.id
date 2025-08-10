<?php

namespace App\Filament\Resources\MustawaResource\Pages;

use App\Filament\Resources\MustawaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMustawa extends EditRecord
{
    protected static string $resource = MustawaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
