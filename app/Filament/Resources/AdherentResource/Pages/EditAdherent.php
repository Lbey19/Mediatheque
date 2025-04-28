<?php

namespace App\Filament\Resources\AdherentResource\Pages;

use App\Filament\Resources\AdherentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdherent extends EditRecord
{
    protected static string $resource = AdherentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
