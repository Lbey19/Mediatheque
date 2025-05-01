<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LivreResource\Pages;
use App\Models\Livre;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
class LivreResource extends Resource

{
    protected static ?string $model = Livre::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informations de base')
                    ->columns(2)
                    ->schema([
                        TextInput::make('titre')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        
                        TextInput::make('auteur')
                            ->required()
                            ->maxLength(255),
                            
                        Select::make('genre')
                            ->options([
                                'roman' => 'Roman',
                                'essai' => 'Essai',
                                'bd' => 'BD',
                                'jeunesse' => 'Jeunesse',
                                'autre' => 'Autre',
                            ])
                            ->required()
                            ->native(false)
                    ]),
                    
                Forms\Components\Section::make('Stock & Disponibilité')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nb_exemplaires')
                            ->numeric()
                            ->minValue(0)
                            ->default(1),
                            
                        Toggle::make('disponible')
                            ->default(true)
                            ->inline(false)
                            ->disabled(fn ($get) => $get('nb_exemplaires') <= 0),
                    ]),
                    
                Forms\Components\Section::make('Image de couverture')
                    ->collapsible()
                    ->schema([
                        FileUpload::make('image')
                            ->label('')
                            ->directory('livres')
                            ->disk('public')
                            ->image()
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('3:4') // Ratio standard pour les livres
                            ->imageResizeTargetWidth('200')
                            ->imageResizeTargetHeight('300')
                            ->imagePreviewHeight('300')
                            ->loadingIndicatorPosition('left')
                            ->panelLayout('integrated')
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadProgressIndicatorPosition('left')
                            ->openable()
                            ->downloadable()
                            ->maxSize(2048)
                            ->maxWidth('200px')
                            ->extraAttributes(['class' => 'bg-gray-50 p-4 rounded-xl'])
                            ->helperText('Format recommandé : 200x300px (ratio 2:3)'),
                    ]),
                    
                Forms\Components\Section::make('Description')
                    ->schema([
                        Textarea::make('description')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                    Forms\Components\Section::make('Informations complémentaires')
                    ->columns(2)
                    ->schema([
                        TextInput::make('isbn')
                            ->label('ISBN')
                            ->maxLength(20)
                            ->nullable(),
                
                        TextInput::make('nombre_pages')
                            ->label('Nombre de pages')
                            ->numeric()
                            ->minValue(1)
                            ->nullable(),
                
                        TextInput::make('edition')
                            ->label('Édition')
                            ->maxLength(100)
                            ->nullable(),
                    ]),
            ]);
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('image')
                ->label('Couverture')
                ->html()
                ->formatStateUsing(function ($state) {
                    $url = $state ? asset('storage/' . $state) : null;
                    $imageContent = self::getImageContent($url);
                    
                    return <<<HTML
                        <div class="relative group h-16 w-12 mx-auto"> <!-- Réduit de h-20 w-14 -->
                            <div class="h-full w-full bg-gray-100 rounded-md shadow-sm overflow-hidden border border-gray-200"> <!-- Bordure plus fine -->
                                {$imageContent}
                            </div>
                            <div class="absolute inset-0 bg-black/5 group-hover:bg-black/10 transition-colors rounded-md"></div>
                        </div>
                    HTML;
                })
                ->extraAttributes(['class' => '!py-2']) 
                ->alignCenter(),
                
                TextColumn::make('titre')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record) => $record->auteur),
                
                TextColumn::make('genre')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'roman' => 'sky',
                        'essai' => 'amber',
                        'bd' => 'emerald',
                        'jeunesse' => 'rose',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'roman' => 'heroicon-o-bookmark',
                        'essai' => 'heroicon-o-academic-cap',
                        'bd' => 'heroicon-o-sparkles',
                        'jeunesse' => 'heroicon-o-cake',
                        default => 'heroicon-o-question-mark-circle',
                    }),
                
                TextColumn::make('nb_exemplaires')
                    ->numeric()
                    ->alignCenter()
                    ->color(fn ($record) => $record->nb_exemplaires > 0 ? 'success' : 'danger')
                    ->icon(fn ($record) => $record->nb_exemplaires > 0 ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'),
                
                    IconColumn::make('disponible')
                    ->boolean()
                    ->alignCenter()
                    ->sortable()
                    ->color(function ($record) {
                        // Si nb_exemplaires <= 0, couleur rouge (danger)
                        return $record->nb_exemplaires > 0 && $record->disponible ? 'success' : 'danger';
                    })
                    ->icon(fn ($record) => $record->nb_exemplaires > 0 && $record->disponible ? 'heroicon-o-check-badge' : 'heroicon-o-no-symbol'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('genre')
                    ->options([
                        'roman' => 'Roman',
                        'essai' => 'Essai',
                        'bd' => 'BD',
                        'jeunesse' => 'Jeunesse',
                        'autre' => 'Autre',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('disponible')
                    ->label('Disponibilité'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil-square')
                    ->button()
                    ->color('gray')
                    ->size('sm'),
                    
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->color('sky')
                    ->size('sm'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('titre', 'asc');
    }

        private static function getImageContent(?string $url): string
    {
        if (!$url) {
            return <<<HTML
                <div class="h-full w-full flex items-center justify-center bg-gray-50">
                    <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            HTML;
        }

        return <<<HTML
            <img src="$url" 
                class="h-full w-full object-cover transform transition-transform duration-300 group-hover:scale-105" />
        HTML;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLivres::route('/'),
            'create' => Pages\CreateLivre::route('/create'),
            'edit' => Pages\EditLivre::route('/{record}/edit'),
        ];
    }
}