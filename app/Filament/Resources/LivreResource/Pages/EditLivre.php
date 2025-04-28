<?php

namespace App\Filament\Resources\LivreResource\Pages;

use App\Filament\Resources\LivreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLivre extends EditRecord
{
    protected static string $resource = LivreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
