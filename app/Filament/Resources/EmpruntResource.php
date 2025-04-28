<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpruntResource\Pages;
use App\Models\Adherent;
use App\Models\Livre;
use App\Models\Emprunt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class EmpruntResource extends Resource
{
    protected static ?string $model = Emprunt::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('livre_id')
                    ->label('Livre')
                    ->relationship('livre', 'titre')
                    ->required(),
                Select::make('adherent_id')
                    ->label('Adhérent')
                    ->relationship('adherent', 'nom')
                    ->required(),
                DatePicker::make('date_emprunt')
                    ->label('Date d\'emprunt')
                    ->required(),
                DatePicker::make('date_retour_prevue')
                    ->label('Date retour prévue')
                    ->required(),
                DatePicker::make('date_retour_effective')
                    ->label('Date retour effective')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('livre.titre')
                    ->label('Livre'),
                Tables\Columns\TextColumn::make('adherent.nom')
                    ->label('Adhérent'),
                Tables\Columns\TextColumn::make('date_emprunt')
                    ->date()
                    ->label('Date d\'emprunt'),
                Tables\Columns\TextColumn::make('date_retour_prevue')
                    ->date()
                    ->label('Retour prévu'),
                Tables\Columns\TextColumn::make('date_retour_effective')
                    ->date()
                    ->label('Retour effectif'),
                Tables\Columns\TextColumn::make('livre.nb_exemplaires')
                    ->label('Exemplaires restants'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('retourner')
                    ->label('Retourner le livre')
                    ->action(function (Emprunt $record) {
                        $record->date_retour_effective = now();
                        $record->save();
                    })
                    ->visible(fn (Emprunt $record) => $record->date_retour_effective === null)
                    ->color('success')
                    ->icon('heroicon-o-check-circle'),
            ])            
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmprunts::route('/'),
            'create' => Pages\CreateEmprunt::route('/create'),
            'edit' => Pages\EditEmprunt::route('/{record}/edit'),
        ];
    }
}


