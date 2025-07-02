<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Emprunt;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn; // Importez TextColumn

class OverdueLoansTable extends BaseWidget
{
    protected static ?int $sort = 3; // Pour l'afficher après les autres widgets
    protected int | string | array $columnSpan = 'full'; // Pour qu'il prenne toute la largeur

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Requête pour récupérer les emprunts non retournés et dont la date de retour est passée
                Emprunt::query()
                    ->whereNull('date_retour_effective')
                    ->where('date_retour_prevue', '<', Carbon::today())
                    ->with(['user', 'livre', 'cd']) // Charger les relations pour éviter N+1
                    ->latest('date_retour_prevue') // Trier par date de retour prévue (les plus anciens retards en premier)
            )
            ->heading('Emprunts en Retard') // Titre du tableau
            ->columns([
                TextColumn::make('user.name')
                    ->label('Adhérent')
                    ->searchable()
                    ->sortable(),

                // Colonne conditionnelle pour afficher le titre du livre ou du CD
                TextColumn::make('item_title')
                    ->label('Média Emprunté')
                    ->getStateUsing(function (Emprunt $record): string {
                        if ($record->livre) {
                            return $record->livre->titre . ' (Livre)';
                        } elseif ($record->cd) {
                            return $record->cd->titre . ' (CD)';
                        }
                        // Ajoutez ici une condition pour les DVDs si vous les implémentez
                        // elseif ($record->dvd) {
                        //     return $record->dvd->titre . ' (DVD)';
                        // }
                        return 'Média inconnu';
                    })
                    ->searchable(query: function ($query, string $search) {
                        // Recherche basique dans les titres de livres et CDs
                        return $query->whereHas('livre', fn($q) => $q->where('titre', 'like', "%{$search}%"))
                                     ->orWhereHas('cd', fn($q) => $q->where('titre', 'like', "%{$search}%"));
                    }),


                TextColumn::make('date_emprunt')
                    ->label('Date d\'emprunt')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('date_retour_prevue')
                    ->label('Date Retour Prévue')
                    ->date('d/m/Y')
                    ->color('danger') // Met en évidence la date dépassée
                    ->sortable(),

                // Colonne calculée pour afficher le nombre de jours de retard
                TextColumn::make('days_overdue')
                    ->label('Jours de Retard')
                    ->getStateUsing(function (Emprunt $record): int {
                        return Carbon::today()->diffInDays($record->date_retour_prevue);
                    })
                    ->color('danger')
                    ->sortable(), // Permet de trier par nombre de jours de retard
            ])
            ->defaultSort('date_retour_prevue', 'asc') // Tri par défaut
            ->paginated(false); // Optionnel: désactiver la pagination si vous voulez tout voir
            // ->defaultPaginationPageOption(5); // Ou limiter à 5 par page par défaut
    }
}