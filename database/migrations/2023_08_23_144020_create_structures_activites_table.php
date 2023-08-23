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
        Schema::create('structures_activites', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('discipline_id');
            $table->integer('structure_id');
            $table->integer('categorie_id');
            $table->string('titre')->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->boolean('actif')->default(true);
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
        Schema::dropIfExists('structures_activites');
    }
};
