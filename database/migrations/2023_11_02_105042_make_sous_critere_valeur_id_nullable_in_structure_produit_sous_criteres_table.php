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
            $table->unsignedBigInteger('sous_critere_valeur_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('structure_produit_sous_criteres', function (Blueprint $table) {
            //
        });
    }
};
