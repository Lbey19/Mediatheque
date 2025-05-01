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
            // Ajouter la colonne cd_id, nullable, après livre_id (si livre_id existe)
            $table->foreignId('cd_id')
                  ->nullable() // Important: un emprunt est soit un livre, soit un CD
                  ->after('livre_id') // Place la colonne après livre_id (optionnel, ajustez si livre_id n'existe pas ou a un autre nom)
                  ->constrained('cds') // Ajoute une clé étrangère vers la table 'cds'
                  ->onDelete('cascade'); // Ou 'set null' si vous préférez
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emprunts', function (Blueprint $table) {
            // Supprimer la clé étrangère et la colonne cd_id
            $table->dropForeign(['cd_id']);
            $table->dropColumn('cd_id');
        });
    }
};