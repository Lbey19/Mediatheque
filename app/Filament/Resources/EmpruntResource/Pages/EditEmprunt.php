<?php

namespace App\Filament\Resources\EmpruntResource\Pages;

use App\Filament\Resources\EmpruntResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmprunt extends EditRecord
{
    protected static string $resource = EmpruntResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
