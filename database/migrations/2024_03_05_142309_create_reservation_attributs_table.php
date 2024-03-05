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
        Schema::create('reservation_attributs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained(
                table: 'reservations',
            )->onDelete('cascade');
            $table->foreignId('booking_field_id')->constrained(
                table: 'liens_dis_cat_tar_booking_fields',
            )->onDelete('cascade');
            $table->foreignId('booking_field_valeur_id')->nullable()->constrained(
                table: 'liens_dis_cat_tar_booking_field_valeurs',
            )->onDelete('cascade');
            $table->string('valeur');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_attributs');
    }
};
