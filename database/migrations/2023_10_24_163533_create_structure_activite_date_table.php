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
        Schema::create('structure_activite_date', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('structure_activite_id');
            $table->date('dayopen')->nullable();
            $table->date('dayclose')->nullable();
            $table->time('houropen')->nullable();
            $table->time('hourclose')->nullable();
            $table->date('date_debut')->nullable();
            $table->time('time_debut')->nullable();
            $table->date('start_month')->nullable();
            $table->date('end_month')->nullable();
            $table->timestamps();

            $table->foreign('structure_activite_id')->references('id')->on('structures_activites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('structure_activite_date');
    }
};
