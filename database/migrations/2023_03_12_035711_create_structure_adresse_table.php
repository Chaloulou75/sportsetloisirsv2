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
        Schema::create('structure_adresse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('structure_id')->constrained()->onDelete('cascade');
            $table->string('address');
            $table->string('zip_code', 20);
            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('villes_france')->onDelete('cascade');
            $table->string('ville');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('a_liste_pays')->onDelete('cascade');
            $table->string('pays');
            $table->double('address_lat');
            $table->double('address_lng');
            $table->string('phone', 30);
            $table->string('email');
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
        Schema::dropIfExists('structure_adresse');
    }
};
