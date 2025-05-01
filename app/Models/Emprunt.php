<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon; // Importer Carbon

class Emprunt extends Model
{
    use HasFactory;

    protected $fillable = [
        'livre_id',
        'user_id', // Assurez-vous que c'est bien user_id ici
        'cd_id',
        'date_emprunt',
        'date_retour_prevue',
        'date_retour_effective',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_emprunt' => 'datetime',
        'date_retour_prevue' => 'datetime',
        'date_retour_effective' => 'datetime',
    ];

    /**
     * Relation: Un emprunt appartient à un livre.
     */
    public function livre(): BelongsTo
    {
        return $this->belongsTo(Livre::class);
    }

    public function cd() // <<< AJOUTER CETTE MÉTHODE
    {
        return $this->belongsTo(Cd::class);
    }

    /**
     * Relation: Un emprunt appartient à un utilisateur (adhérent).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // --- ACCESSEURS ---

    /**
     * Accesseur pour déterminer si l'emprunt est en retard.
     *
     * @return bool
     */
    public function getIsOverdueAttribute(): bool
    {
        // Est en retard si la date de retour prévue est passée ET qu'il n'a pas été rendu
        return is_null($this->date_retour_effective) && $this->date_retour_prevue->isPast();
    }

    /**
     * Accesseur pour obtenir le label du statut.
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        if (!is_null($this->date_retour_effective)) {
            return 'Rendu';
        }

        if ($this->is_overdue) { // Utilise l'accesseur is_overdue
            return 'En retard';
        }

        return 'En cours';
    }

    /**
     * Accesseur pour obtenir la couleur associée au statut (pour Tailwind CSS).
     *
     * @return string
     */
    public function getStatusColorAttribute(): string
    {
        if (!is_null($this->date_retour_effective)) {
            return 'green'; // Rendu
        }

        if ($this->is_overdue) { // Utilise l'accesseur is_overdue
            return 'red'; // En retard
        }

        return 'blue'; // En cours (ou 'yellow' si vous préférez)
    }

    public function getStatusIconAttribute(): string
    {
        if (!is_null($this->date_retour_effective)) {
            return 'heroicon-o-check-circle'; // Rendu
        }

        if ($this->is_overdue) {
            return 'heroicon-o-exclamation-triangle'; // En retard
        }

        return 'heroicon-o-clock'; // En cours
    }
}