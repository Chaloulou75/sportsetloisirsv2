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
        Schema::table('liens_disciplines_categories_criteres', function (Blueprint $table) {
            $table->boolean('visible_back')->default(true)->after('type_champ_form');
            $table->boolean('visible_front')->default(true)->after('visible_back');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liens_disciplines_categories_criteres', function (Blueprint $table) {
            $table->dropColumn(['visible_back', 'visible_front']);
        });
    }
};
