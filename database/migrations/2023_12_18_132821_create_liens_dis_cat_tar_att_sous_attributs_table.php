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
        Schema::create('liens_dis_cat_tar_att_sous_attributs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cat_tar_att_id')->constrained(
                table: 'liens_dis_cat_tartyp_attributs',
            )->onUpdate('cascade')->onDelete('cascade');
            $table->string('nom');
            $table->string('type_champ_form');
            $table->unsignedInteger('ordre')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liens_dis_cat_tar_att_sous_attributs');
    }
};
