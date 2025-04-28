<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdherentResource\Pages;
use App\Models\Adherent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class AdherentResource extends Resource
{
    protected static ?string $model = Adherent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nom')->required(),
                TextInput::make('prenom')->required(),
                TextInput::make('email')->email()->required(),
                TextInput::make('telephone')->tel(),
                TextInput::make('adresse'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')->label('Nom')->searchable()->sortable(),
                TextColumn::make('prenom')->label('Prénom')->searchable()->sortable(),
                TextColumn::make('email')->label('Email')->searchable()->sortable(),
                TextColumn::make('telephone')->label('Téléphone')->searchable()->sortable(),
                TextColumn::make('adresse')->label('Adresse')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdherents::route('/'),
            'create' => Pages\CreateAdherent::route('/create'),
            'edit' => Pages\EditAdherent::route('/{record}/edit'),
        ];
    }
}
