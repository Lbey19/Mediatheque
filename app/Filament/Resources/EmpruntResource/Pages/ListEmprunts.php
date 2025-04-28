<?php

namespace App\Filament\Resources\EmpruntResource\Pages;

use App\Filament\Resources\EmpruntResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmprunts extends ListRecords
{
    protected static string $resource = EmpruntResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
