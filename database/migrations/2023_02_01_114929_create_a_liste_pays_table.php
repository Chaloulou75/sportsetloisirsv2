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
        Schema::create('a_liste_pays', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('code');
            $table->string('alpha2', 2);
            $table->string('alpha3', 3);
            $table->string('nom_en_gb', 45);
            $table->string('nom', 45);
            $table->integer('defaut');
            $table->integer('tva');
            $table->decimal('taux_tva', 4);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('a_liste_pays');
    }
};
