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
        Schema::create('reservation_sous_attributs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained(
                table: 'reservations',
            )->onDelete('cascade');
            $table->foreignId('reservation_attribut_id')->constrained(
                table: 'reservation_attributs',
            )->onDelete('cascade');
            $table->foreignId('booking_field_ss_field_id')->constrained(
                table: 'liens_dis_cat_tar_book_field_ss_fields',
            )->onDelete('cascade');
            $table->foreignId('booking_ss_field_valeur_id')->nullable()->constrained(
                table: 'liens_d_c_t_booking_field_ss_field_valeurs',
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
        Schema::dropIfExists('reservation_sous_attributs');
    }
};
