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
        Schema::create('structures_cat_tar_attributs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('str_cat_tar_id')->constrained(
                table: 'structures_cat_tarifs',
            )->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('cat_tar_att_id')->constrained(
                table: 'liens_dis_cat_tartyp_attributs',
            )->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('dis_cat_tar_att_valeur_id')->nullable()->constrained(
                table: 'liens_dis_cat_tar_att_valeurs',
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
        Schema::dropIfExists('structures_cat_tar_attributs');
    }
};
