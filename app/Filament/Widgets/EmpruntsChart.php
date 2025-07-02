<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Emprunt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Assurez-vous d'importer DB

class EmpruntsChart extends ChartWidget
{
    protected static ?string $heading = 'Emprunts des 7 derniers jours'; // Titre du graphique
    protected static ?int $sort = 2; // Pour l'ordre d'affichage (après StatsOverview si celui-ci a 1 ou pas de sort)
    protected static string $color = 'primary'; // Couleur d'accentuation

    protected function getData(): array
    {
        // Récupérer les données des emprunts des 7 derniers jours, groupés par jour
        $data = Emprunt::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            // Transformer les données pour le graphique (clé = date, valeur = count)
            ->pluck('count', 'date');

        // Préparer les labels (les 7 derniers jours)
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $labels[] = Carbon::now()->subDays($i)->format('d/m'); // Format jour/mois
        }

        // Préparer les datasets (les comptes pour chaque jour, 0 si pas d'emprunt ce jour-là)
        $datasets = [
            [
                'label' => 'Emprunts effectués',
                'data' => [],
                'borderColor' => '#36A2EB', // Couleur de la ligne
                'backgroundColor' => 'rgba(54, 162, 235, 0.2)', // Couleur de remplissage (optionnel)
                'fill' => 'start', // Remplir sous la ligne
            ],
        ];

        // Remplir le dataset avec les données réelles ou 0
        $currentDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        while ($currentDate <= $endDate) {
            $formattedDate = $currentDate->toDateString(); // Format YYYY-MM-DD pour la clé $data
            $datasets[0]['data'][] = $data->get($formattedDate, 0); // Utilise get() avec 0 par défaut
            $currentDate->addDay();
        }


        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        // Type de graphique : 'line', 'bar', 'pie', 'doughnut', 'radar', 'polarArea'
        return 'line';
    }
}