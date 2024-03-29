<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('liens_dis_cat_tar_booking_field_valeurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cat_tar_field_id')->constrained(
                table: 'liens_dis_cat_tar_booking_fields',
            )->onUpdate('cascade')->onDelete('cascade');
            $table->string('valeur');
            $table->unsignedInteger('ordre')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lien_dis_cat_tar_booking_field_valeurs');
    }
};
