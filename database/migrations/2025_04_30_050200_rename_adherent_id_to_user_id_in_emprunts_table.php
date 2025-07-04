<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('emprunts', function (Blueprint $table) {
            $table->renameColumn('adherent_id', 'user_id');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('emprunts', function (Blueprint $table) {
            $table->renameColumn('user_id', 'adherent_id');
        });
    }
};
