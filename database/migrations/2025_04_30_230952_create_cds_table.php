<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cds', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('artiste');
            $table->string('genre')->nullable();
            $table->integer('nb_pistes')->nullable();
            $table->string('duree')->nullable(); // Format "42:19"
            $table->date('date_sortie')->nullable();
            $table->string('image')->nullable();
            $table->integer('nb_exemplaires')->default(1); // Ajouté
            $table->boolean('disponible')->default(true); // Ajouté
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cds');
    }
};