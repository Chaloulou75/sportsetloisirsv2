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
        Schema::create('liens_dis_cat_crit_val_ss_crit_valeur', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dcc_val_ss_crit_id');
            $table->string('valeur');
            $table->boolean('defaut');
            $table->timestamps();

            $table->foreign('dcc_val_ss_crit_id')->references('id')->on('liens_dis_cat_crit_val_sous_criteres')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liens_dis_cat_crit_val_ss_crit_valeur');
    }
};
