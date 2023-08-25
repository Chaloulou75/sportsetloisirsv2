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
        Schema::create('liste_disciplines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('famille')->nullable();
            $table->string('sous_famille', 250)->nullable();
            $table->string('name');
            $table->string('slug');
            $table->string('type')->nullable();
            $table->string('prefixe_de')->nullable();
            $table->string('prefixe_du')->nullable();
            $table->string('prefixe_le_la')->nullable();
            $table->string('club')->nullable();
            $table->string('lieu')->nullable();
            $table->string('coach')->nullable();
            $table->string('pratiquant')->nullable();
            $table->string('clubs')->nullable();
            $table->string('lieux')->nullable();
            $table->string('coachs')->nullable();
            $table->string('pratiquants')->nullable();
            $table->string('title')->nullable();
            $table->string('title_clubs')->nullable();
            $table->string('title_profs')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_description_clubs')->nullable();
            $table->string('meta_description_profs')->nullable();
            $table->string('h1')->nullable();
            $table->string('h1_clubs')->nullable();
            $table->string('h1_profs')->nullable();
            $table->string('h2')->nullable();
            $table->string('h2_clubs')->nullable();
            $table->string('h2_profs')->nullable();
            $table->text('description');
            $table->text('description2')->nullable();
            $table->text('description_clubs')->nullable();
            $table->text('description_profs')->nullable();
            $table->integer('popularite')->nullable();
            $table->integer('fait')->nullable();
            $table->integer('nb_clubs')->nullable();
            $table->integer('nb_profs')->nullable();
            $table->integer('nb_lieux')->nullable();
            $table->integer('nb_events')->nullable();
            $table->integer('nb_articles')->nullable();
            $table->unsignedBigInteger('view_count')->default(0);
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
        Schema::dropIfExists('liste_disciplines');
    }
};
