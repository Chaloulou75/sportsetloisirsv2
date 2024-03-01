<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['produit_id']);
            $table->dropForeign(['tarif_id']);
            $table->dropForeign(['planning_id']);

            $table->dropColumn('user_id');
            $table->dropColumn('produit_id');
            $table->dropColumn('tarif_id');
            $table->dropColumn('planning_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            //
        });
    }
};
