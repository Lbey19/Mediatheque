<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpruntResource\Pages;
use App\Models\Emprunt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Livre;
use App\Models\Cd; // <<< AJOUTER : Importer le modèle Cd
use Filament\Forms\Get; // <<< AJOUTER : Pour la validation conditionnelle
use Filament\Forms\Set; // <<< AJOUTER : Pour vider l'autre champ
use Filament\Forms\Components\Actions\Action as FormAction; // <<< AJOUTER : Pour le bouton reset du formulaire (renommé pour éviter conflit avec Tables\Actions\Action)

class EmpruntResource extends Resource
{
    protected static ?string $model = Emprunt::class;
    protected static ?string $navigationIcon = 'heroicon-o-bookmark';
    // Optionnel: Déplacer dans un groupe
    // protected static ?string $navigationGroup = 'Gestion Médiathèque';
    // protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Détails de l\'emprunt')
                    ->columns(2)
                    ->schema([
                        // --- MODIFICATION : Remplacer le Select Livre unique ---
                        Forms\Components\Select::make('livre_id')
                            ->relationship('livre', 'titre', modifyQueryUsing: fn (Builder $query) => $query->where('nb_exemplaires', '>', 0)->orderBy('titre')) // Afficher seulement les livres disponibles
                            ->searchable(['titre', 'auteur'])
                            ->preload()
                            ->label('Livre (si applicable)') // Libellé modifié
                            ->live() // Important pour la logique conditionnelle
                            ->afterStateUpdated(fn (Set $set) => $set('cd_id', null)) // Vider le champ CD si un livre est choisi
                            ->required(fn (Get $get): bool => !$get('cd_id')) // Requis si cd_id est vide
                            ->suffixAction( // Bouton pour vider la sélection
                                fn ($state, Set $set) =>
                                    FormAction::make('clear_livre')
                                        ->icon('heroicon-m-x-mark')
                                        ->tooltip('Effacer la sélection')
                                        ->action(fn () => $set('livre_id', null))
                                        ->visible($state !== null)
                            ),

                        Forms\Components\Select::make('cd_id') // <<< AJOUTER : Select pour les CDs
                            ->relationship('cd', 'titre', modifyQueryUsing: fn (Builder $query) => $query->where('nb_exemplaires', '>', 0)->orderBy('titre')) // Afficher seulement les CDs disponibles
                            ->searchable(['titre', 'artiste'])
                            ->preload()
                            ->label('CD (si applicable)') // Libellé
                            ->live() // Important pour la logique conditionnelle
                            ->afterStateUpdated(fn (Set $set) => $set('livre_id', null)) // Vider le champ Livre si un CD est choisi
                            ->required(fn (Get $get): bool => !$get('livre_id')) // Requis si livre_id est vide
                            ->suffixAction( // Bouton pour vider la sélection
                                fn ($state, Set $set) =>
                                    FormAction::make('clear_cd')
                                        ->icon('heroicon-m-x-mark')
                                        ->tooltip('Effacer la sélection')
                                        ->action(fn () => $set('cd_id', null))
                                        ->visible($state !== null)
                            ),
                        // --- FIN MODIFICATION ---

                        Forms\Components\Select::make('user_id')
                            ->label('Adhérent')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\DatePicker::make('date_emprunt')
                            ->label('Date d\'emprunt')
                            ->default(now())
                            ->required()
                            ->maxDate(now()),

                        Forms\Components\DatePicker::make('date_retour_prevue')
                            ->label('Date retour prévue')
                            ->required()
                            ->minDate(fn (Forms\Get $get): Carbon => Carbon::parse($get('date_emprunt'))->addDay())
                            ->default(fn (Forms\Get $get): Carbon => Carbon::parse($get('date_emprunt'))->addWeeks(2)), // Garder 2 semaines par défaut

                        Forms\Components\DatePicker::make('date_retour_effective')
                            ->label('Date retour effectif')
                            ->nullable()
                            ->minDate(fn (Forms\Get $get): Carbon => Carbon::parse($get('date_emprunt'))),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date_retour_effective', 'asc')
            ->columns([
                // --- MODIFICATION : Colonne Article générique ---
                Tables\Columns\TextColumn::make('item') // Colonne virtuelle 'item'
                    ->label('Article Emprunté')
                    ->getStateUsing(function (Emprunt $record): string {
                        if ($record->livre) {
                            return $record->livre->titre;
                        } elseif ($record->cd) {
                            return $record->cd->titre;
                        }
                        return 'Article inconnu';
                    })
                    ->description(function (Emprunt $record): string { // Description pour auteur/artiste
                        if ($record->livre) {
                            return 'Livre de ' . ($record->livre->auteur ?? 'Auteur inconnu');
                        } elseif ($record->cd) {
                            return 'CD par ' . ($record->cd->artiste ?? 'Artiste inconnu');
                        }
                        return '';
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        // Recherche sur les titres/auteurs/artistes des relations
                        return $query
                            ->whereHas('livre', fn($q) => $q->where('titre', 'like', "%{$search}%")->orWhere('auteur', 'like', "%{$search}%"))
                            ->orWhereHas('cd', fn($q) => $q->where('titre', 'like', "%{$search}%")->orWhere('artiste', 'like', "%{$search}%"));
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                         // Tri un peu plus complexe car sur deux relations
                         // Jointure peut être une option, ou tri en PHP si peu de données, ou tri simple sur une colonne principale
                         // Ici, on trie sur le titre du livre OU du cd (peut mélanger un peu)
                         return $query
                            ->leftJoin('livres', 'emprunts.livre_id', '=', 'livres.id')
                            ->leftJoin('cds', 'emprunts.cd_id', '=', 'cds.id')
                            ->orderByRaw('COALESCE(livres.titre, cds.titre) ' . $direction)
                            ->select('emprunts.*'); // Important de re-sélectionner les colonnes d'emprunts
                    }),
                // --- FIN MODIFICATION ---

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Adhérent')
                    ->searchable(),

                Tables\Columns\TextColumn::make('date_emprunt')
                    ->label('Emprunté le')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('date_retour_prevue')
                    ->label('Retour prévu')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn (Emprunt $record): ?string => !$record->date_retour_effective && $record->date_retour_prevue?->isPast() ? 'danger' : null) // Utilisation de ?-> pour éviter erreur si null
                    ->tooltip(fn (Emprunt $record): ?string => !$record->date_retour_effective && $record->date_retour_prevue?->isPast() ? 'En retard' : null),

                Tables\Columns\TextColumn::make('date_retour_effective')
                    ->label('Retour effectif')
                    ->date('d/m/Y')
                    ->placeholder('Non retourné')
                    ->sortable(),

                Tables\Columns\TextColumn::make('statut_calculé') // Gardé tel quel
                    ->label('Statut')
                    ->badge()
                    ->state(function (Emprunt $record): string {
                        if ($record->date_retour_effective) {
                            return 'Retourné';
                        }
                        if ($record->date_retour_prevue?->isPast()) { // Utilisation de ?->
                            return 'En retard';
                        }
                        return 'En cours';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Retourné' => 'success',
                        'En retard' => 'danger',
                        'En cours' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('statut_retour') // Gardé tel quel
                    ->label('Statut Retour')
                    ->options([
                        'non_retourne' => 'Non Retourné',
                        'retourne' => 'Retourné',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'];
                        if ($value === null) return $query;
                        if ($value === 'retourne') return $query->whereNotNull('date_retour_effective');
                        if ($value === 'non_retourne') return $query->whereNull('date_retour_effective');
                        return $query;
                    }),

                Tables\Filters\Filter::make('emprunts_en_retard_specifique') // Gardé tel quel
                    ->label('Seulement en retard')
                    ->query(fn (Builder $query) => $query->where('date_retour_prevue', '<', now())
                        ->whereNull('date_retour_effective')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->color('gray'),

                // --- MODIFICATION : Action Retourner ---
                Tables\Actions\Action::make('retourner')
                    ->label('Retourner Article') // Libellé générique
                    ->action(function (Emprunt $record) {
                        if ($record->date_retour_effective === null) {
                            $record->update(['date_retour_effective' => now()]);
                            // Incrémenter le stock de l'article retourné
                            if ($record->livre) {
                                $record->livre->increment('nb_exemplaires');
                            } elseif ($record->cd) { // <<< AJOUTER : Gérer le retour de CD
                                $record->cd->increment('nb_exemplaires');
                            }
                        }
                    })
                    ->visible(fn (Emprunt $record): bool => $record->date_retour_effective === null)
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation(),
                // --- FIN MODIFICATION ---
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                    // Ajouter une action groupée pour retourner plusieurs articles ?
                ]),
            ])
            // <<< AJOUTER : Charger les relations pour la table >>>
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['user', 'livre', 'cd']));
    }

    public static function getPages(): array
    {
        // ... (votre méthode getPages reste inchangée) ...
        return [
            'index' => Pages\ListEmprunts::route('/'),
            'create' => Pages\CreateEmprunt::route('/create'),
            'edit' => Pages\EditEmprunt::route('/{record}/edit'),
        ];
    }

}