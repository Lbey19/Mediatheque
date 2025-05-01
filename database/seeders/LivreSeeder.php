<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Livre; // Assurez-vous que le modèle Livre est importé

class LivreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Livre::create([
            'titre' => 'Le Seigneur des Anneaux',
            'auteur' => 'J.R.R. Tolkien',
            'isbn' => '978-2070612880',
            'nb_exemplaires' => 5, // <-- Correction: nb_exemplaires
            'genre' => 'Fantasy',
            // 'date_publication' => '1954-07-29', // Ces champs ne sont pas dans la migration create_livres_table
            // 'editeur' => 'Christian Bourgois Editeur', // Ces champs ne sont pas dans la migration create_livres_table
            'description' => 'Une grande épopée de fantasy.',
            'image' => null, // Assurez-vous que le champ image existe (migration add_image_to_livres_table)
            // 'nombre_pages' => null, // Ces champs sont dans la migration add_infos_to_livres_table
            // 'edition' => null, // Ces champs sont dans la migration add_infos_to_livres_table
        ]);

        Livre::create([
            'titre' => '1984',
            'auteur' => 'George Orwell',
            'isbn' => '978-2070368228',
            'nb_exemplaires' => 3, // <-- Correction: nb_exemplaires
            'genre' => 'Dystopie',
            // 'date_publication' => '1949-06-08',
            // 'editeur' => 'Gallimard',
            'description' => 'Un roman d\'anticipation glaçant.',
            'image' => null,
            // 'nombre_pages' => null,
            // 'edition' => null,
        ]);

        // Ajoutez d'autres livres si vous le souhaitez
        // Livre::factory()->count(10)->create(); // Si vous avez une factory Livre
    }
}