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
        Schema::create('liens_dis_cat_crit_val_sous_criteres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dis_cat_crit_val_id');
            $table->string('nom');
            $table->string('type_champ_form');
            $table->timestamps();

            $table->foreign('dis_cat_crit_val_id')->references('id')->on('liens_disciplines_categories_criteres_valeurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liens_dis_cat_crit_val_sous_criteres');
    }
};
