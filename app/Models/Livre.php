<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Livre extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'auteur',
        'genre',
        'description',
        'nb_exemplaires',
        'disponible',
        'image',
        'isbn',
        'nombre_pages',
        'edition',
    ];

    protected $appends = ['image_url'];

    protected static function booted()
    {
        // Met à jour la disponibilité automatiquement
        static::saving(function ($livre) {
            $livre->disponible = $livre->nb_exemplaires > 0;
        });

        // Supprime le fichier physique quand l'image est retirée
        static::updating(function ($livre) {
            if ($livre->isDirty('image') && $livre->getOriginal('image')) {
                Storage::disk('public')->delete($livre->getOriginal('image'));
            }
        });
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    public function emprunts()
    {
    return $this->hasMany(Emprunt::class);
    }

    public function prochainRetourPrevu()
    {
        return $this->emprunts()
            ->whereNull('date_retour_effective')
            ->orderBy('date_retour_prevue')
            ->value('date_retour_prevue');
    }
}