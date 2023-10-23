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
        Schema::table('villes_france', function (Blueprint $table) {
            $table->index('ville');
            $table->index('ville_formatee');
            $table->index('code_postal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('villes_france', function (Blueprint $table) {
            $table->dropIndex('villes_france_ville_index');
            $table->dropIndex('villes_france_ville_formatee_index');
            $table->dropIndex('villes_france_code_postal_index');
        });
    }
};
