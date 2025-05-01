<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EmpruntsRelationManager extends RelationManager
{
    protected static string $relationship = 'emprunts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('livre_id')
                    ->relationship('livre', 'titre')
                    ->required(),
                Forms\Components\DatePicker::make('date_emprunt')
                    ->required(),
                Forms\Components\DatePicker::make('date_retour_prevue')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('livre.titre'),
                Tables\Columns\TextColumn::make('date_emprunt')->date(),
                Tables\Columns\TextColumn::make('date_retour_prevue')->date(),
                Tables\Columns\IconColumn::make('rendu')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !is_null($record->date_retour_effective)),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}