<?php

namespace App\Filament\Pages;

// Importe la classe de base du Dashboard Filament
use Filament\Pages\Dashboard as BaseDashboard;
// Importe ton widget de statistiques
use App\Filament\Widgets\EmpruntStatsOverview;
// Importe les classes de base pour les widgets
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;

// Ta classe Dashboard hérite de celle de Filament
class Dashboard extends BaseDashboard
{
    /**
     * Retourne la liste des widgets à afficher sur cette page.
     *
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        // Retourne un tableau contenant la classe de ton widget
        return [
            EmpruntStatsOverview::class,
            // Tu pourrais ajouter d'autres widgets ici si tu en avais
            // Par exemple : \Filament\Widgets\AccountWidget::class,
        ];
    }

    /**
     * Optionnel : Définit le nombre de colonnes pour les widgets.
     *
     * @return int | string | array<string, int | string | null>
     */
    public function getColumns(): int | string | array
    {
        // Par défaut, Filament utilise une disposition intelligente,
        // mais tu peux forcer un nombre de colonnes, par exemple 2 ou 3.
        // Pour les widgets "stats overview", ils s'adaptent généralement bien.
        return 'auto'; // ou 2, 3, etc.
    }
}