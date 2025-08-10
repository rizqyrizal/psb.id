<?php

namespace App\Filament\Resources\AbsensiiResource\Pages;

use App\Filament\Resources\AbsensiiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAbsensiis extends ListRecords
{
    protected static string $resource = AbsensiiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
