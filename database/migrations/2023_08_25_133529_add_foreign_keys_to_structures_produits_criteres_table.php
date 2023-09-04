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
        Schema::table('structures_produits_criteres', function (Blueprint $table) {

            $table->foreign('structure_id')
                    ->references('id')
                    ->on('structures')
                    ->onDelete('cascade');
            $table->foreign('discipline_id')
                  ->references('id')
                  ->on('liste_disciplines')
                  ->onDelete('cascade');
            $table->foreign('categorie_id')
                  ->references('id')
                  ->on('liens_disciplines_categories')
                  ->onDelete('cascade');
            $table->foreign('activite_id')
                  ->references('id')
                  ->on('structures_activites')
                  ->onDelete('cascade');
            $table->foreign('produit_id')
                  ->references('id')
                  ->on('structures_produits')
                  ->onDelete('cascade');
            $table->foreign('critere_id')
                  ->references('id')
                  ->on('liens_disciplines_categories_criteres')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('structures_produits_criteres', function (Blueprint $table) {

            $table->dropForeign(['structure_id']);
            $table->dropForeign(['discipline_id']);
            $table->dropForeign(['categorie_id']);
            $table->dropForeign(['activite_id']);
            $table->dropForeign(['produit_id']);
            $table->dropForeign(['critere_id']);

        });
    }
};