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
        Schema::create('liens_disciplines_categories_criteres_valeurs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('discipline_categorie_critere_id');
            $table->string('valeur');
            $table->boolean('defaut');
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
        Schema::dropIfExists('liens_disciplines_categories_criteres_valeurs');
    }
};
