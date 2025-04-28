<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprunt extends Model
{
    use HasFactory;

    protected $fillable = [
        'livre_id',
        'adherent_id',
        'date_emprunt',
        'date_retour_prevue',
        'date_retour_effective',
    ];

    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }

    public function adherent()
    {
        return $this->belongsTo(Adherent::class);
    }

    protected static function booted()
    {
        static::creating(function ($emprunt) {
            $livre = Livre::find($emprunt->livre_id);

            if (!$livre || $livre->nb_exemplaires <= 0) {
                throw new \Exception('Ce livre n\'est pas disponible actuellement.');
            }

            // Si le livre est dispo on décrémente les exemplaires
            $livre->decrement('nb_exemplaires');
        });

        static::deleting(function ($emprunt) {
            // Quand un emprunt est supprimé on remet un exemplaire
            $livre = Livre::find($emprunt->livre_id);

            if ($livre) {
                $livre->increment('nb_exemplaires');
            }
        });
    }
}
