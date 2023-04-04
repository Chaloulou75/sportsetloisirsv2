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
        Schema::create('structure_villes_france', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('structure_id');
            $table->unsignedBigInteger('villes_france_id');
            $table->timestamps();

            $table->foreign('structure_id')->references('id')->on('structures')->onDelete('cascade');
            $table->foreign('villes_france_id')->references('id')->on('villes_france')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('structure_villes_france');
    }
};
