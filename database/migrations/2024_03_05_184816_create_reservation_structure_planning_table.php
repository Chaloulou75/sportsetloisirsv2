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
        Schema::create('reservation_structure_planning', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained(table: 'reservations')->onDelete('cascade');
            $table->foreignId('planning_id')->constrained(table: 'structure_produits_planning')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_structure_planning');
    }
};
