<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('emprunts', function (Blueprint $table) {
            // Ajouter les colonnes polymorphiques
            // 'morphs' crée les colonnes empruntable_id (UNSIGNED BIGINT) et empruntable_type (VARCHAR)
            $table->morphs('empruntable');

            // Rendre l'ancienne colonne livre_id nullable (si elle existe et n'est pas déjà nullable)
            // Vous devrez peut-être adapter ceci en fonction de votre migration initiale pour emprunts
            // Si vous avez déjà renommé adherent_id en user_id, livre_id devrait exister.
            if (Schema::hasColumn('emprunts', 'livre_id')) {
                $table->unsignedBigInteger('livre_id')->nullable()->change();
                // Optionnel : Supprimer la contrainte de clé étrangère si elle existe
                // $table->dropForeign(['livre_id']);
            }
             // Optionnel : Supprimer la colonne livre_id si vous êtes sûr de ne plus en avoir besoin
             // $table->dropColumn('livre_id');
        });

        // --- IMPORTANT : Migrer les données existantes ---
        // Si vous avez déjà des emprunts de livres, vous devez remplir les nouvelles colonnes
        // Il est plus sûr de le faire avec un Seeder ou une commande Artisan après la migration,
        // mais pour un petit nombre de données, on peut le faire ici (avec précaution).
        // DB::table('emprunts')->whereNotNull('livre_id')->update([
        //     'empruntable_id' => DB::raw('livre_id'),
        //     'empruntable_type' => \App\Models\Livre::class,
        // ]);
        // ATTENTION : L'utilisation de DB::raw peut varier selon le SGBD.
        // Il est VIVEMENT recommandé de faire une sauvegarde avant d'exécuter cette migration.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emprunts', function (Blueprint $table) {
            // Supprimer les colonnes polymorphiques
            $table->dropMorphs('empruntable');

            // Rétablir l'ancienne colonne livre_id (si vous l'aviez supprimée)
            // ou la rendre non nullable (si vous l'aviez juste rendue nullable)
            // if (Schema::hasColumn('emprunts', 'livre_id')) {
            //     $table->unsignedBigInteger('livre_id')->nullable(false)->change();
                 // Recréer la clé étrangère si nécessaire
                 // $table->foreign('livre_id')->references('id')->on('livres')->onDelete('cascade'); // ou set null
            // }
        });
    }
};