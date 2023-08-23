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
        Schema::create('liens_disciplines_categories', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('discipline_id');
            $table->integer('categorie_id');
            $table->string('nom_categorie_pro')->nullable();
            $table->string('nom_categorie_client')->nullable();
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
        Schema::dropIfExists('liens_disciplines_categories');
    }
};
