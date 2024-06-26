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
            $table->string('min')->nullable()->after('unite');
            $table->string('max')->nullable()->after('min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liens_disciplines_categories_criteres', function (Blueprint $table) {
            $table->dropColumn(['min', 'max']);
        });
    }
};
