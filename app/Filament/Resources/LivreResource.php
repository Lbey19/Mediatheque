<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LivreResource\Pages;
use App\Models\Livre;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class LivreResource extends Resource
{
    protected static ?string $model = Livre::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('titre')
                    ->required()
                    ->columnSpanFull(),
                
                TextInput::make('auteur')
                    ->required()
                    ->columnSpanFull(),
                
                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
                
                Select::make('genre')
                    ->options([
                        'roman' => 'Roman',
                        'essai' => 'Essai',
                        'bd' => 'BD',
                        'jeunesse' => 'Jeunesse',
                        'autre' => 'Autre',
                    ])
                    ->required()
                    ->native(false),
                
                TextInput::make('nb_exemplaires')
                    ->numeric()
                    ->minValue(1),
                
                Toggle::make('disponible')
                    ->default(true)
                    ->inline(false),
                
                FileUpload::make('image')
                    ->label('Couverture du livre')
                    ->directory('livres')
                    ->disk('public')
                    ->image()
                    ->imageEditor()
                    ->imagePreviewHeight('250')
                    ->loadingIndicatorPosition('left')
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadProgressIndicatorPosition('left')
                    ->openable()
                    ->downloadable()
                    ->maxSize(2048)
                    ->columnSpanFull()
                    ->extraAttributes(['class' => 'bg-gray-50 p-4 rounded-lg']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titre')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                
                TextColumn::make('auteur')
                    ->toggleable()
                    ->searchable(),
                
                TextColumn::make('genre')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'roman' => 'info',
                        'essai' => 'warning',
                        'bd' => 'success',
                        'jeunesse' => 'danger',
                        default => 'gray',
                    }),
                
                TextColumn::make('nb_exemplaires')
                    ->numeric()
                    ->alignCenter(),
                
                IconColumn::make('disponible')
                    ->boolean()
                    ->alignCenter()
                    ->sortable(),
                
                TextColumn::make('image')
                    ->label('Couverture')
                    ->html()
                    ->formatStateUsing(function ($state) {
                        if (!$state) {
                            return <<<HTML
                                <div class="px-3 py-2 bg-gray-100/50 rounded-full inline-flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-gray-500">Aucune image</span>
                                </div>
                            HTML;
                        }

                        $url = asset('storage/' . $state);

                        return <<<HTML
                            <div class="relative group">
                                <a href="{$url}" target="_blank" class="block">
                                    <div class="overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-200">
                                        <img src="{$url}" 
                                            alt="Couverture du livre" 
                                            class="h-20 w-14 object-cover transform transition-all duration-300 group-hover:scale-105
                                                   bg-gray-50"
                                            style="
                                                clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
                                                backface-visibility: hidden;
                                            "
                                        />
                                    </div>
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-all rounded-lg"></div>
                                </a>
                            </div>
                        HTML;
                    })
                    ->extraAttributes(['class' => '!py-2'])
                    ->alignCenter(),
            ])
            ->filters([
                // Filters
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->button()
                    ->color('gray'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Relations
        ];
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