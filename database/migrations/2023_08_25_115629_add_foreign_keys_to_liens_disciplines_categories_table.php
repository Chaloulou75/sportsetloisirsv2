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

            $table->foreign('discipline_id')
                ->references('id')
                ->on('liste_disciplines')
                ->onDelete('cascade');
            $table->foreign('categorie_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liens_disciplines_categories', function (Blueprint $table) {

            $table->dropForeign(['discipline_id']);
            $table->dropForeign(['categorie_id']);

        });
    }
};
