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
        Schema::create('structure_produits_planning', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('structure_id');
            $table->unsignedBigInteger('discipline_id');
            $table->unsignedBigInteger('categorie_id');
            $table->unsignedBigInteger('activite_id');
            $table->unsignedBigInteger('produit_id');
            $table->string('title');
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('structure_produits_planning');
    }
};
