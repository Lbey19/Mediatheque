<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Emprunt;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn; // Pour afficher le statut
use Carbon\Carbon;

class LatestLoansTable extends BaseWidget
{
    protected static ?int $sort = 4; // Pour l'afficher en dernier
    protected int | string | array $columnSpan = 'full'; // Prend toute la largeur

    protected static ?string $heading = 'Derniers Emprunts Effectués'; // Titre

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Récupérer les 10 derniers emprunts créés
                Emprunt::query()
                    ->with(['user', 'livre', 'cd']) // Charger les relations
                    ->latest('created_at') // Trier par date de création (les plus récents en premier)
                    ->limit(10) // Limiter aux 10 plus récents
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('Adhérent')
                    ->searchable()
                    ->sortable(),

                // Colonne pour le titre du média (livre ou CD)
                TextColumn::make('item_title')
                    ->label('Média Emprunté')
                    ->getStateUsing(function (Emprunt $record): string {
                        if ($record->livre) {
                            return $record->livre->titre . ' (Livre)';
                        } elseif ($record->cd) {
                            return $record->cd->titre . ' (CD)';
                        }
                        // Ajoutez DVD ici si nécessaire
                        return 'Média inconnu';
                    })
                    ->searchable(query: function ($query, string $search) {
                        return $query->whereHas('livre', fn($q) => $q->where('titre', 'like', "%{$search}%"))
                                     ->orWhereHas('cd', fn($q) => $q->where('titre', 'like', "%{$search}%"));
                    }),

                TextColumn::make('created_at') // Utilise created_at pour "dernier emprunt"
                    ->label('Date d\'emprunt')
                    ->dateTime('d/m/Y H:i') // Affiche date et heure
                    ->sortable(),

                TextColumn::make('date_retour_prevue')
                    ->label('Retour Prévu')
                    ->date('d/m/Y')
                    ->sortable(),

                // Colonne pour afficher le statut (En cours, En retard, Retourné)
                BadgeColumn::make('status')
                    ->label('Statut')
                    ->getStateUsing(function (Emprunt $record): string {
                        if ($record->date_retour_effective) {
                            return 'Retourné';
                        } elseif ($record->date_retour_prevue < Carbon::today()) {
                            return 'En retard';
                        } else {
                            return 'En cours';
                        }
                    })
                    ->colors([
                        'success' => 'Retourné',
                        'warning' => 'En cours',
                        'danger' => 'En retard',
                    ])
                    ->sortable(), // Permet de trier par statut
            ])
            ->paginated(false); // Pas de pagination pour cette liste courte
            // ->defaultSort('created_at', 'desc'); // Tri par défaut déjà fait par latest() dans la query
    }
}