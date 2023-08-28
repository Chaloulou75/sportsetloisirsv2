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
        Schema::table('produit_tarif', function (Blueprint $table) {

            $table->foreign('produit_id')->references('id')->on('structures_produits')->onDelete('cascade');
            $table->foreign('tarif_id')->references('id')->on('structures_tarifs')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produit_tarif', function (Blueprint $table) {

            $table->dropForeign(['produit_id']);
            $table->dropForeign(['tarif_id']);

        });
    }
};
