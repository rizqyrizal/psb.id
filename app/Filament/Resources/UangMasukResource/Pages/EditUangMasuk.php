<?php

namespace App\Filament\Resources\UangMasukResource\Pages;

use App\Filament\Resources\UangMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUangMasuk extends EditRecord
{
    protected static string $resource = UangMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
