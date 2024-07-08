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
        Schema::table('structures_types_infos', function (Blueprint $table) {
            $table->foreignId('valeur_id')->nullable()->after('attribut_id')->constrained(table: 'liste_structures_types_valeurs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('structures_types_infos', function (Blueprint $table) {
            $table->dropColumn('valeur_id');
        });
    }
};
