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
        Schema::create('structures_produits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('structure_id');
            $table->unsignedBigInteger('discipline_id');
            $table->unsignedBigInteger('categorie_id');
            $table->unsignedBigInteger('activite_id');
            $table->unsignedBigInteger('lieu_id');
            $table->boolean('actif');
            $table->unsignedBigInteger('horaire_id')->nullable();
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
