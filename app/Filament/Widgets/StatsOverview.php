<?php


namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Livre;
use App\Models\Cd;
use App\Models\Emprunt;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Utilisateurs', User::count())
                ->description('Nombre total d\'adhérents inscrits')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Livres', Livre::count())
                ->description('Nombre de titres de livres uniques')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary'),

            Stat::make('Total CDs', Cd::count())
                ->description('Nombre de titres de CDs uniques')
                ->descriptionIcon('heroicon-m-musical-note') // Ou heroicon-m-circle-stack
                ->color('info'),

            Stat::make('Emprunts Actifs', Emprunt::whereNull('date_retour_effective')->count())
                ->description('Nombre de médias actuellement empruntés')
                ->descriptionIcon('heroicon-m-arrow-up-on-square-stack')
                ->color('warning'),

            Stat::make('Emprunts en Retard', Emprunt::whereNull('date_retour_effective')
                                                    ->where('date_retour_prevue', '<', Carbon::today())
                                                    ->count())
                ->description('Nombre d\'emprunts non retournés à temps')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}