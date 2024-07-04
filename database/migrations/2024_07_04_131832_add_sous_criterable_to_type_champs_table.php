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
        Schema::table('type_champs', function (Blueprint $table) {
            $table->boolean('sous_criterable')->default(true)->after('criterable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('type_champs', function (Blueprint $table) {
            $table->dropColumn('sous_criterable');
        });
    }
};
