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
        Schema::create('structures_cat_tar_att_ssattr', function (Blueprint $table) {
            $table->id();
            $table->foreignId('str_cat_tar_att_id')->constrained(
                table: 'structures_cat_tar_attributs',
            )->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('sousattribut_id')->constrained(
                table: 'liens_dis_cat_tar_att_sous_attributs',
            )->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('ss_att_valeur_id')->nullable()->constrained(
                table: 'liens_dis_cat_tar_att_ssattr_valeurs',
            )->onUpdate('cascade')->onDelete('cascade');
            $table->string('valeur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('structures_cat_tar_att_ssattr');
    }
};
