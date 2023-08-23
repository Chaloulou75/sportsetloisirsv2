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
        Schema::create('liens_disciplines_categories_criteres', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('discipline_id');
            $table->integer('categorie_id');
            $table->integer('critere_id')->nullable();
            $table->string('nom');
            $table->string('type_champ_form');
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
        Schema::dropIfExists('liens_disciplines_categories_criteres');
    }
};
