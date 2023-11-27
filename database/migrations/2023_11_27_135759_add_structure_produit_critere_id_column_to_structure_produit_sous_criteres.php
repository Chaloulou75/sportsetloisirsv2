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
        Schema::table('structure_produit_sous_criteres', function (Blueprint $table) {
            $table->unsignedBigInteger('prod_crit_id')->after('critere_id')->nullable();
            $table->foreign('prod_crit_id')->references('id')->on('structures_produits_criteres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('structure_produit_sous_criteres', function (Blueprint $table) {
            $table->dropColumn('prod_crit_id');
        });
    }
};
