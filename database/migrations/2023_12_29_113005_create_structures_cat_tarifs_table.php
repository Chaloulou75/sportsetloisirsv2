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
        Schema::create('structures_cat_tarifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('structure_id')->constrained(
                table: 'structures',
            )->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('categorie_id')->constrained(
                table: 'liens_disciplines_categories',
            )->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('dis_cat_tar_typ_id')->constrained(
                table: 'liens_dis_cat_tariftypes',
            )->onUpdate('cascade')->onDelete('cascade');
            $table->string('titre')->nullable();
            $table->text('description')->nullable();
            $table->decimal('amount', 7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('structures_cat_tarifs');
    }
};
