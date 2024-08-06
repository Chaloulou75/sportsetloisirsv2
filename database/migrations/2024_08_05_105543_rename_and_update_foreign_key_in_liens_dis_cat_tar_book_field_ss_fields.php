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
        Schema::table('liens_dis_cat_tar_book_field_ss_fields', function (Blueprint $table) {
            if (Schema::hasColumn('liens_dis_cat_tar_book_field_ss_fields', 'booking_field_id')) {
                $table->dropForeign(['booking_field_id']);
                $table->renameColumn('booking_field_id', 'field_valeur_id');
            }

            if (Schema::hasColumn('liens_dis_cat_tar_book_field_ss_fields', 'field_valeur_id')) {
                $table->unsignedBigInteger('field_valeur_id')->change();
                $table->foreign('field_valeur_id')
                      ->references('id')
                      ->on('liens_dis_cat_tar_booking_field_valeurs')
                      ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liens_dis_cat_tar_book_field_ss_fields', function (Blueprint $table) {
            if (Schema::hasColumn('liens_dis_cat_tar_book_field_ss_fields', 'field_valeur_id')) {
                $table->dropForeign(['field_valeur_id']);
                $table->renameColumn('field_valeur_id', 'booking_field_id');
                $table->foreign('booking_field_id')
                      ->references('id')
                      ->on('liens_dis_cat_tar_booking_fields')
                      ->onDelete('cascade');
            }
        });
    }
};
