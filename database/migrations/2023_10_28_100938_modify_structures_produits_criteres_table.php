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

        Schema::table('structures_produits_criteres', function (Blueprint $table) {
            $table->unsignedBigInteger('valeur_id')->after('critere_id')->nullable();
            $table->foreign('valeur_id')->references('id')->on('liens_disciplines_categories_criteres_valeurs')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('structures_produits_criteres', function (Blueprint $table) {
            $table->dropColumn('valeur_id');
        });

    }
};
