<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('liens_disciplines_categories_criteres_valeurs', function (Blueprint $table) {
            $table->foreign('discipline_categorie_critere_id')
                  ->references('id')
                  ->on('liens_disciplines_categories_criteres')
                  ->onDelete('cascade')
                  ->name('fk_discipline_categorie_critere');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liens_disciplines_categories_criteres_valeurs', function (Blueprint $table) {
            $table->dropForeign(['discipline_categorie_critere_id']);

        });
    }
};
