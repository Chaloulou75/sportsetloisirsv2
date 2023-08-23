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
        Schema::create('liste_familles', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->string('slug');
            $table->string('nom_long');
            $table->string('title');
            $table->string('title_clubs');
            $table->string('title_profs');
            $table->string('meta_description');
            $table->string('meta_description_clubs');
            $table->string('meta_description_profs');
            $table->string('h1');
            $table->string('h1_clubs');
            $table->string('h1_profs');
            $table->string('h2');
            $table->string('h2_clubs');
            $table->string('h2_profs');
            $table->text('description');
            $table->text('description_clubs');
            $table->text('description_profs');
            $table->string('type');
            $table->integer('ordre');
            $table->unsignedInteger('view_count')->nullable();
            $table->timestamp('created_at')->useCurrent();
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
        Schema::dropIfExists('liste_familles');
    }
};
