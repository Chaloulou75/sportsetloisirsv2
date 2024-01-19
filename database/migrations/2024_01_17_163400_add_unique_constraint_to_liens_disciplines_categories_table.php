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
        Schema::table('liens_disciplines_categories', function (Blueprint $table) {
            $table->unique(['categorie_id', 'discipline_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liens_disciplines_categories', function (Blueprint $table) {
            $table->dropUnique(['categorie_id', 'discipline_id']);
        });
    }
};