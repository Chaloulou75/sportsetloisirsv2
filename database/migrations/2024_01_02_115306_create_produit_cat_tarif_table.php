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
        Schema::create('produit_cat_tarif', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained(
                table: 'structures_produits',
            )->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('cat_tarif_id')->constrained(
                table: 'structures_cat_tarifs',
            )->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('structure_produit_structure_cat_tarif');
    }
};
