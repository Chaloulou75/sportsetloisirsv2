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
            $table->bigIncrements('id');
            $table->string('departement');
            $table->string('numero', 10);
            $table->string('latitude', 200);
            $table->string('longitude', 200);
            $table->string('prefixe');
            $table->integer('nb_clubs');
            $table->integer('nb_profs');
            $table->integer('nb_lieux');
            $table->integer('nb_events');
            $table->unsignedBigInteger('view_count')->default(0)->index();
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
        Schema::dropIfExists('departements');
    }
};
