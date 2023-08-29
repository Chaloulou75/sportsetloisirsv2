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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained(table: 'users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('produit_id')
                    ->constrained(table: 'structures_produits')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreignId('tarif_id')
                    ->constrained(table: 'structures_tarifs')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreignId('planning_id')
                            ->constrained(table: 'structure_produits_planning')
                            ->onUpdate('cascade')
                            ->onDelete('cascade');

            $table->boolean('pending')->default(true);
            $table->boolean('confirmed')->default(false);
            $table->boolean('finished')->default(false);
            $table->boolean('cancelled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
