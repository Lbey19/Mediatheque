<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CdResource\Pages;
// use App\Filament\Resources\CdResource\RelationManagers; // Décommenter si vous ajoutez des relations plus tard
use App\Models\Cd;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload; // <<< Assurez-vous que ceci est importé
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn; // <<< Assurez-vous que ceci est importé
use Filament\Tables\Columns\IconColumn;

class CdResource extends Resource
{
    protected static ?string $model = Cd::class;

    protected static ?string $navigationIcon = 'heroicon-o-musical-note'; // Icône pour CD
    protected static ?string $navigationGroup = 'Gestion Médiathèque'; // Même groupe que Livres
    protected static ?int $navigationSort = 2; // Placer après Livres (si LivreResource a sort = 1)

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informations principales')
                    ->columns(2)
                    ->schema([
                        TextInput::make('titre')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(), // Prend toute la largeur
                        TextInput::make('artiste')
                            ->required()
                            ->maxLength(255),
                        // Exemple avec un Select pour le genre, adaptez les options
                        Select::make('genre')
                            ->options([
                                'rock' => 'Rock',
                                'pop' => 'Pop',
                                'jazz' => 'Jazz',
                                'classique' => 'Classique',
                                'electro' => 'Electro',
                                'rap' => 'Rap/Hip-Hop',
                                'bande originale' => 'Bande Originale',
                                'autre' => 'Autre',
                            ])
                            ->searchable(),
                        TextInput::make('nb_pistes')
                            ->label('Nombre de pistes')
                            ->numeric()
                            ->minValue(1)
                            ->nullable(), // Rendre nullable si ce n'est pas toujours requis
                        TextInput::make('duree')
                            ->label('Durée (ex: 42:19)')
                            ->placeholder('HH:MM:SS ou MM:SS')
                            ->maxLength(8) // Ajuster si nécessaire
                            ->nullable(), // Rendre nullable si ce n'est pas toujours requis
                        DatePicker::make('date_sortie')
                            ->label('Date de sortie')
                            ->nullable(), // Rendre nullable si ce n'est pas toujours requis
                    ]),

                Section::make('Stock & Disponibilité')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nb_exemplaires')
                            ->label('Nombre d\'exemplaires')
                            ->numeric()
                            ->required()
                            ->minValue(0) // Permet 0 exemplaire
                            ->default(1),
                        // La disponibilité est gérée automatiquement par le modèle Cd via 'saving'
                        Toggle::make('disponible')
                            ->label('Disponible (calculé)')
                            ->disabled() // Désactivé car calculé
                            ->helperText('Mis à jour automatiquement selon le nombre d\'exemplaires.'),
                    ]),

                Section::make('Image de la pochette')
                    ->collapsible()
                    ->schema([
                        // <<< Configuration du FileUpload >>>
                        FileUpload::make('image')
                            ->label('Fichier image')
                            ->disk('public') // Utilise le disque public configuré
                            ->directory('cd-images') // Stocke dans storage/app/public/cd-images
                            ->image() // Valide que c'est une image et active l'aperçu
                            ->imageEditor() // Optionnel: active un éditeur simple
                            ->maxSize(2048) // Optionnel: limite la taille (ici 2MB)
                            ->columnSpanFull(), // Prend toute la largeur de la section
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // <<< Configuration de ImageColumn >>>
                ImageColumn::make('image')
                    ->label('Pochette')
                    ->disk('public') // Spécifier le disque public
                    ->square() // Afficher en carré
                    ->height(60), // Hauteur de l'image dans la table

                TextColumn::make('titre')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn (Cd $record): string => $record->artiste ?? ''), // Afficher l'artiste en description

                TextColumn::make('genre')
                    ->badge() // Afficher comme badge
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nb_exemplaires')
                    ->label('Stock')
                    ->sortable()
                    ->alignCenter(), // Centrer

                IconColumn::make('disponible')
                    ->label('Dispo.')
                    ->boolean() // Afficher comme icône booléenne
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(), // Centrer
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('genre')
                    ->options(fn () => Cd::distinct()->pluck('genre', 'genre')->filter()->sort()->toArray()) // Options basées sur les genres existants
                    ->multiple(), // Permettre sélection multiple
                Tables\Filters\TernaryFilter::make('disponible')
                    ->label('Disponibilité'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // Action pour voir les détails
                Tables\Actions\EditAction::make(), // Action pour modifier
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('artiste', 'asc'); // Trier par artiste par défaut
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\EmpruntsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCds::route('/'),
            'create' => Pages\CreateCd::route('/create'),
            // 'view' => Pages\ViewCd::route('/{record}'), // Commentez ou supprimez si vous n'avez pas de page View dédiée
            'edit' => Pages\EditCd::route('/{record}/edit'),
        ];
    }
}