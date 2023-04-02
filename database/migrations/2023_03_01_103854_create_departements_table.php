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
        Schema::create('departements', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('departement');
            $table->string('numero', 10);
            $table->string('latitude', 200);
            $table->string('longitude', 200);
            $table->string('prefixe');
            $table->integer('nb_clubs');
            $table->integer('nb_profs');
            $table->integer('nb_lieux');
            $table->integer('nb_events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departements');
    }
};
