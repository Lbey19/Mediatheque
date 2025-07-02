<?php


namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CombinedLoanStats extends Widget
{
    protected static string $view = 'filament.widgets.combined-loan-stats'; // Vue Blade à utiliser
    protected int | string | array $columnSpan = 'full'; // Prend toute la largeur
    protected static ?int $sort = 3; // Ordre d'affichage (ajustez si nécessaire)
    protected static bool $isLazy = false; // Charger immédiatement
}