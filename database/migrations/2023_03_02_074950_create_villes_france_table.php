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
        Schema::create('villes_france', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('code_postal', 10);
            $table->string('code_insee', 10);
            $table->string('article', 20);
            $table->string('ville_sans_article');
            $table->string('ville');
            $table->string('ville_formatee');
            $table->integer('id_unique_ville');
            $table->string('article_maj', 20);
            $table->string('ville_maj');
            $table->string('libelle');
            $table->integer('region');
            $table->string('nom_region');
            $table->string('departement', 10);
            $table->string('nom_departement');
            $table->float('latitude', 10, 0);
            $table->float('longitude', 10, 0);
            $table->string('codex', 20);
            $table->string('metaphone', 20);
            $table->integer('tolerance_rayon');
            $table->integer('nb_clubs');
            $table->integer('nb_profs');
            $table->integer('nb_lieux');
            $table->integer('nb_events');

            $table->index(['latitude', 'longitude'], 'latitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('villes_france');
    }
};
