<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\EmpruntsChart;
use App\Filament\Widgets\CombinedLoanStats; 
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;

class Dashboard extends BaseDashboard
{
    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            EmpruntsChart::class,
            CombinedLoanStats::class,
        ];
    }

    /**
     * Optionnel : Définit le nombre de colonnes pour les widgets.
     *
     * @return int | string | array<string, int | string | null>
     */
    public function getColumns(): int | string | array
    {
        
        return 'auto'; 
    }
}