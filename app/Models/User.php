<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'prenom',
        'email',
        'password',
        'telephone',
        'adresse',
        'ville',
        'code_postal',
        'date_inscription',
        'date_expiration',
        'actif',
        'role', // 'admin' ou 'adherent'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_inscription' => 'date',
            'date_expiration' => 'date',
            'actif' => 'boolean',
        ];
    }

    /**
     * Vérifie si l'utilisateur est un administrateur/employé
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est un adhérent
     */
    public function isAdherent(): bool
    {
        return $this->role === 'adherent';
    }

    /**
     * Vérifie si l'utilisateur est un employé (admin ou employé)
     */
    public function isEmployee(): bool
    {
        return in_array($this->role, ['admin', 'employee']);
    }

    /**
     * Nom complet : Prénom + Nom
     */
    public function getNomCompletAttribute(): string
    {
        return trim($this->prenom . ' ' . $this->name);
    }

    /**
     * Vérifie si l'adhésion a expiré
     */
    public function getAdhesionExpireeAttribute(): bool
    {
        return $this->date_expiration !== null && $this->date_expiration->isPast();
    }

    protected static function booted()
    {
        static::retrieved(function ($user) {
            if ($user->adhesion_expiree && $user->actif) {
                $user->actif = false;
                $user->save();
            }
        });
    }

    /**
     * Adresse courte : Code postal + Ville
     */
    public function getAdresseCourteAttribute(): string
    {
        return trim($this->code_postal . ' ' . $this->ville);
    }

    /**
     * Relation avec les emprunts (à activer si tu as une table 'emprunts')
     */
    public function emprunts()
    {
        return $this->hasMany(\App\Models\Emprunt::class, 'user_id');
    }
}
