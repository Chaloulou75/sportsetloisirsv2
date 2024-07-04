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
        Schema::table('liens_dis_cat_crit_val_sous_criteres', function (Blueprint $table) {
            $table->foreignId('type_champ_id')->nullable()->after('type_champ_form')->constrained()->cascadeOnDelete();
            $table->string('unite')->nullable()->after('type_champ_id');
            $table->string('min')->nullable()->after('unite');
            $table->string('max')->nullable()->after('min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liens_dis_cat_crit_val_sous_criteres', function (Blueprint $table) {
            $table->dropColumn(['type_champ_id', 'unite', 'min', 'max']);
        });
    }
};
