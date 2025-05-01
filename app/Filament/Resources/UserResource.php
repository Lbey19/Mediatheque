<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\EmpruntsRelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Membres';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations personnelles')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(50),

                        Forms\Components\TextInput::make('prenom')
                            ->label('Prénom')
                            ->required()
                            ->maxLength(50),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('telephone')
                            ->tel()
                            ->mask('99-99-99-99-99')
                            ->placeholder('06-12-34-56-78'),

                        // ✅ Champ rôle bien défini
                        Forms\Components\Select::make('role')
                            ->label('Rôle')
                            ->required()
                            ->options([
                                'admin' => 'Admin',
                                'employee' => 'Employé',
                                'adherent' => 'Adhérent',
                            ]),
                    ]),

                Forms\Components\Section::make('Adresse')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('adresse')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('code_postal')
                            ->mask('99999')
                            ->maxLength(5),

                        Forms\Components\TextInput::make('ville')
                            ->maxLength(50),
                    ]),

                Forms\Components\Section::make('Adhésion')
                    ->schema([
                        Forms\Components\DatePicker::make('date_inscription')
                            ->default(now())
                            ->required(),

                        Forms\Components\DatePicker::make('date_expiration')
                            ->required()
                            ->minDate(fn ($get) => $get('date_inscription')),

                        Forms\Components\Toggle::make('actif')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom_complet')
                    ->label('Nom complet')
                    ->searchable(['name', 'prenom'])
                    ->sortable()
                    ->description(fn ($record) => $record->email),

                TextColumn::make('telephone')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('adresse_courte')
                    ->label('Adresse')
                    ->toggleable()
                    ->getStateUsing(fn ($record) =>
                        $record->code_postal . ' ' . $record->ville),

                TextColumn::make('date_expiration')
                    ->label('Expiration adhésion')
                    ->date()
                    ->color(fn ($record) => $record->adhesion_expiree ? 'danger' : 'success')
                    ->sortable(),

                    TextColumn::make('statut')
                    ->label('Statut')
                    ->getStateUsing(function ($record) {
                        if ($record->adhesion_expiree) {
                            return 'Expiré';
                        }
                
                        return $record->actif ? 'Actif' : 'Inactif';
                    })
                    ->icon(function ($record) {
                        if ($record->adhesion_expiree) {
                            return 'heroicon-o-x-circle';
                        }
                
                        return $record->actif ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle';
                    })
                    ->color(function ($record) {
                        if ($record->adhesion_expiree) {
                            return 'danger';
                        }
                
                        return $record->actif ? 'success' : 'gray';
                    })
                    ->tooltip(function ($record) {
                        if ($record->adhesion_expiree) {
                            return 'Adhésion expirée';
                        }
                
                        return $record->actif ? 'Utilisateur actif' : 'Compte inactif';
                    })
                    ->alignCenter(),
                

                TextColumn::make('emprunts_count')
                    ->label('Emprunts')
                    ->counts('emprunts')
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('actif')
                    ->label('Statut actif'),

                Tables\Filters\Filter::make('adhesion_expiree')
                    ->label('Adhésion expirée')
                    ->query(fn ($query) => $query->where('date_expiration', '<', now())),

                Tables\Filters\SelectFilter::make('ville')
                    ->options(fn () => User::pluck('ville', 'ville')->filter()->unique()->toArray()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye'),

                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil'),

                Tables\Actions\Action::make('renouveler')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        Forms\Components\DatePicker::make('nouvelle_date_expiration')
                            ->default(now()->addYear())
                            ->required(),
                    ])
                    ->action(function (User $user, $data) {
                        $user->update([
                            'date_expiration' => $data['nouvelle_date_expiration'],
                            'actif' => true
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            EmpruntsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUser::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
