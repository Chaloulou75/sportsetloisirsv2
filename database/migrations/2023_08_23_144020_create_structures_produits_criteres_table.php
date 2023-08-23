<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('structures_produits_criteres', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('structure_id');
            $table->integer('discipline_id');
            $table->integer('categorie_id');
            $table->integer('activite_id');
            $table->integer('produit_id');
            $table->integer('critere_id');
            $table->text('valeur');
            $table->boolean('defaut')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('structures_produits_criteres');
    }
};
