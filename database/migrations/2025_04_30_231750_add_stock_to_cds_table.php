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
        Schema::table('cds', function (Blueprint $table) {
            $table->integer('nb_exemplaires')->default(1)->after('image');
            $table->boolean('disponible')->default(true)->after('nb_exemplaires');
        });
    }
    public function down(): void
    {
        Schema::table('cds', function (Blueprint $table) {
            $table->dropColumn(['nb_exemplaires', 'disponible']);
        });
    }
};
