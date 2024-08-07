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
        Schema::table('liens_dis_cat_tar_booking_fields', function (Blueprint $table) {
            $table->foreignId('type_champ_id')->nullable()->after('type_champ_form')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liens_dis_cat_tar_booking_fields', function (Blueprint $table) {
            $table->dropColumn('type_champ_id');
        });
    }
};
