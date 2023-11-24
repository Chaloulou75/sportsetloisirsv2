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
            $table->boolean('indexable')->default(false)->after('visible_front');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liens_disciplines_categories_criteres', function (Blueprint $table) {
            $table->dropColumn('indexable');
        });
    }
};
