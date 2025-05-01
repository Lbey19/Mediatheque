<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Ajouté si vous utilisez des factories
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // Ajouté

class Cd extends Model
{
    use HasFactory; // Ajouté si vous utilisez des factories

    protected $fillable = [
        'titre',
        'artiste',
        'genre',
        'nb_pistes',
        'duree',
        'date_sortie',
        'image',
        'nb_exemplaires', // Ajouté
        'disponible',     // Ajouté
    ];

    // Ajouté : Pour gérer l'URL de l'image comme pour Livre
    protected $appends = ['image_url'];

    // Ajouté : Logique similaire à Livre
    protected static function booted()
    {
        // Met à jour la disponibilité automatiquement
        static::saving(function ($cd) {
            $cd->disponible = $cd->nb_exemplaires > 0;
        });

        // Supprime le fichier physique quand l'image est retirée
        static::updating(function ($cd) {
            if ($cd->isDirty('image') && $cd->getOriginal('image')) {
                Storage::disk('public')->delete($cd->getOriginal('image'));
            }
        });

         // Supprime le fichier physique quand le CD est supprimé
        static::deleting(function ($cd) {
            if ($cd->image) {
                Storage::disk('public')->delete($cd->image);
            }
        });
    }

    // Ajouté : Accesseur pour l'URL de l'image
    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    // Ajouté : Relation avec les emprunts (sera modifiée à l'étape 6)
    public function emprunts() // <<< VÉRIFIER/AJOUTER CETTE MÉTHODE
    {
        return $this->hasMany(Emprunt::class);
    }
}