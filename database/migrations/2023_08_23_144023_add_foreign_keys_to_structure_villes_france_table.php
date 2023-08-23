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
        Schema::table('structure_villes_france', function (Blueprint $table) {
            $table->foreign(['structure_id'])->references(['id'])->on('structures')->onDelete('CASCADE');
            $table->foreign(['villes_france_id'])->references(['id'])->on('villes_france')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('structure_villes_france', function (Blueprint $table) {
            $table->dropForeign('structure_villes_france_structure_id_foreign');
            $table->dropForeign('structure_villes_france_villes_france_id_foreign');
        });
    }
};
