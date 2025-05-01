<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Assurez-vous que le modèle User est importé
use Carbon\Carbon; // Importer Carbon pour les dates

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un administrateur
        User::create([
            'name' => 'Admin', // Utiliser 'name' pour le nom de famille
            'prenom' => 'User', // Utiliser 'prenom' pour le prénom
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Mot de passe: password
            'role' => 'admin', // Définir le rôle comme admin
            'email_verified_at' => now(),
            'date_inscription' => Carbon::now(), // Ajouter les champs de la migration adherent_fields
            'date_expiration' => Carbon::now()->addYear(), // Exemple: expiration dans 1 an
            'actif' => true, // L'admin est actif
            // 'telephone' => '0123456789', // Optionnel
            // 'adresse' => '1 rue de l\'admin', // Optionnel
            // 'ville' => 'Montpellier', // Optionnel
            // 'code_postal' => '34000', // Optionnel
            // 'numero_adherent' => 'ADMIN001', // Ce champ n'est pas dans les migrations fournies
            // 'is_admin' => true, // Redondant avec 'role' et isAdmin()
        ]);

        // Créer quelques utilisateurs normaux via la factory (si elle existe et est à jour)
        // Assurez-vous que votre UserFactory inclut les champs 'prenom', 'role', 'actif', etc.
        User::factory()->count(5)->create([
             'role' => 'adherent', // S'assurer que les utilisateurs créés par factory ont le bon rôle
             'actif' => true, // Rendre les utilisateurs de test actifs par défaut
             'date_inscription' => Carbon::now(),
             'date_expiration' => Carbon::now()->addYear(),
        ]);

        // Ou créer manuellement si la factory n'est pas configurée ou à jour
        /*
        User::create([
            'name' => 'Test', // Nom de famille
            'prenom' => 'UserUn', // Prénom
            'email' => 'user1@example.com',
            'password' => Hash::make('password'),
            'role' => 'adherent', // Rôle adhérent
            'email_verified_at' => now(),
            'date_inscription' => Carbon::now(),
            'date_expiration' => Carbon::now()->addYear(),
            'actif' => true,
            // 'numero_adherent' => 'USER001', // Ce champ n'est pas dans les migrations fournies
        ]);
        */
    }
}