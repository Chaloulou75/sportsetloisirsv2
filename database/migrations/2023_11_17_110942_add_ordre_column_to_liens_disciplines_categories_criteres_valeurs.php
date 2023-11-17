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
        Schema::table('liens_disciplines_categories_criteres_valeurs', function (Blueprint $table) {
            $table->unsignedInteger('ordre')->nullable()->after('valeur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liens_disciplines_categories_criteres_valeurs', function (Blueprint $table) {
            $table->dropColumn('ordre');
        });
    }
};
