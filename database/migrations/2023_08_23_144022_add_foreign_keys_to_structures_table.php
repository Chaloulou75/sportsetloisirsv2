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
        Schema::table('structures', function (Blueprint $table) {
            $table->foreign(['structuretype_id'])->references(['id'])->on('structuretypes')->onDelete('CASCADE');
            $table->foreign(['city_id'])->references(['id'])->on('villes_france')->onDelete('CASCADE');
            $table->foreign(['departement_id'])->references(['id'])->on('departements')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('CASCADE');
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
        Schema::table('structures', function (Blueprint $table) {
            $table->dropForeign('structures_structuretype_id_foreign');
            $table->dropForeign('structures_city_id_foreign');
            $table->dropForeign('structures_departement_id_foreign');
            $table->dropForeign('structures_user_id_foreign');
            $table->dropForeign('structures_country_id_foreign');
        });
    }
};
