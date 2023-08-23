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
        Schema::create('structures_produits', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('structure_id');
            $table->integer('discipline_id');
            $table->integer('categorie_id');
            $table->integer('activite_id');
            $table->integer('lieu_id');
            $table->boolean('actif');
            $table->integer('horaire_id')->nullable();
            $table->boolean('reservable')->default(false);
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
        Schema::dropIfExists('structures_produits');
    }
};
