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
        Schema::table('liste_structures_types_attributs', function (Blueprint $table) {

            $table->foreign('structuretype_id')
                  ->references('id')
                  ->on('structuretypes')
                  ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liste_structures_types_attributs', function (Blueprint $table) {
            $table->dropForeign(['structuretype_id']);
        });
    }
};
