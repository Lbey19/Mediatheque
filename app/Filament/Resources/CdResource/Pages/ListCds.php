<?php

namespace App\Filament\Resources\CdResource\Pages;

use App\Filament\Resources\CdResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCds extends ListRecords
{
    protected static string $resource = CdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
