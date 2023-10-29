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
        Schema::create('structure_produit_sous_criteres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activite_id');
            $table->unsignedBigInteger('produit_id');
            $table->unsignedBigInteger('critere_id');
            $table->unsignedBigInteger('critere_valeur_id');
            $table->unsignedBigInteger('sous_critere_id');
            $table->unsignedBigInteger('sous_critere_valeur_id');
            $table->string('valeur');

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
            $table->foreign('critere_valeur_id')
                ->references('id')
                ->on('liens_disciplines_categories_criteres_valeurs')
                ->onDelete('cascade');
            $table->foreign('sous_critere_id')
                ->references('id')
                ->on('liens_dis_cat_crit_val_sous_criteres')
                ->onDelete('cascade');
            $table->foreign('sous_critere_valeur_id')
                    ->references('id')
                    ->on('liens_dis_cat_crit_val_ss_crit_valeur')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('structure_produit_sous_criteres');
    }
};
