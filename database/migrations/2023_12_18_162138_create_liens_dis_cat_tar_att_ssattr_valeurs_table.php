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
        Schema::create('liens_dis_cat_tar_att_ssattr_valeurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sousattribut_id')->constrained(
                table: 'liens_dis_cat_tar_att_sous_attributs',
            )->onUpdate('cascade')->onDelete('cascade');
            $table->string('valeur');
            $table->unsignedInteger('ordre')->nullable();
            $table->boolean('inclus_all')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liens_dis_cat_tar_att_ssattr_valeurs');
    }
};
