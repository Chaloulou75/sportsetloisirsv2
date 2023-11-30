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
        Schema::table('structure_activite_date', function (Blueprint $table) {
            $table->unsignedBigInteger('structure_produit_id')->after('structure_activite_id')->nullable();
            $table->foreign('structure_produit_id')->references('id')->on('structures_produits')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('structure_activite_date', function (Blueprint $table) {
            $table->dropColumn('structure_produit_id');
        });
    }
};
