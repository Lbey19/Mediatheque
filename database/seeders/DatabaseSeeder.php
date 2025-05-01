<?php

namespace Database\Seeders; // Doit être la première ligne après <?php

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appeler les seeders spécifiques
        $this->call([
            UserSeeder::class,
            LivreSeeder::class,
            EmpruntSeeder::class, // Ajoutez cette ligne si vous avez créé EmpruntSeeder
        ]);

        // User::factory(10)->create(); // Vous pouvez commenter ou supprimer ceci si UserSeeder crée déjà des users

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]); // Vous pouvez commenter ou supprimer ceci
    }
}