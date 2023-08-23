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
        Schema::table('structure_adresse', function (Blueprint $table) {
            $table->foreign(['city_id'])->references(['id'])->on('villes_france')->onDelete('CASCADE');
            $table->foreign(['structure_id'])->references(['id'])->on('structures')->onDelete('CASCADE');
            $table->foreign(['country_id'])->references(['id'])->on('liste_pays')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('structure_adresse', function (Blueprint $table) {
            $table->dropForeign('structure_adresse_city_id_foreign');
            $table->dropForeign('structure_adresse_structure_id_foreign');
            $table->dropForeign('structure_adresse_country_id_foreign');
        });
    }
};
