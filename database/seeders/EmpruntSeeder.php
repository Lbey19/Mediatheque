<?php
// filepath: c:\wamp64\www\mediatheque\database\seeders\EmpruntSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Emprunt;
use App\Models\User;
use App\Models\Livre;
use Carbon\Carbon;

class EmpruntSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'user1@example.com')->first(); // Assurez-vous que cet user existe
        $admin = User::where('email', 'admin@example.com')->first();
        $livre1 = Livre::find(1); // Prend le premier livre créé
        $livre2 = Livre::find(2); // Prend le deuxième livre créé

        if ($user && $livre1) {
            // Emprunt en cours
            Emprunt::create([
                'user_id' => $user->id,
                'livre_id' => $livre1->id,
                'date_emprunt' => Carbon::now()->subDays(10),
                'date_retour_prevue' => Carbon::now()->addDays(4),
                'date_retour_effective' => null, // Pas encore rendu
            ]);
        }

        if ($admin && $livre2) {
             // Emprunt historique (rendu)
             Emprunt::create([
                'user_id' => $admin->id,
                'livre_id' => $livre2->id,
                'date_emprunt' => Carbon::now()->subDays(30),
                'date_retour_prevue' => Carbon::now()->subDays(16),
                'date_retour_effective' => Carbon::now()->subDays(15), // Rendu
            ]);
        }
    }
}