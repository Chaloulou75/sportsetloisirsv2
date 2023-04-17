<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ab_structures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom');
            $table->string('slug');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('structuretype_id')->constrained()->onDelete('cascade')->default(1);
            $table->string('adresse');
            $table->integer('afficher_adresse');
            $table->double('address_lat');
            $table->double('address_lng');
            $table->string('code_postal', 20);
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('villes_france')->onDelete('cascade');
            $table->string('ville');
            $table->foreignId('departement_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('a_liste_pays')->onDelete('cascade');
            $table->string('pays');
            $table->string('contact');
            $table->string('telephone1', 30)->nullable();
            $table->string('telephone2', 30)->nullable();
            $table->string('email');
            $table->string('email_sauvegarde');
            $table->text('presentation_courte');
            $table->text('presentation_longue')->nullable();
            $table->string('photo');
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('web')->nullable();
            $table->boolean('premium')->nullable();
            $table->integer('rank')->nullable();
            $table->boolean('valide_client')->default(false);
            $table->boolean('valide_admin')->default(false);
            $table->boolean('valide_update')->default(false);
            $table->boolean('abo_news')->default(true);
            $table->boolean('abo_promo')->default(true);
            $table->timestamp('date_actif');
            $table->boolean('ajout_admin');
            $table->integer('nb_activites')->default(0);
            $table->integer('nb_produits')->default(0);
            $table->integer('nb_reservations')->default(0);
            $table->bigInteger('nb_vues')->unsigned()->default(0)->index();
            $table->decimal('moyenne_notes', 2, 1);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('a_structures');
    }
};
