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
        Schema::table('liens_disciplines_similaires', function (Blueprint $table) {

            $table->foreign('discipline_id')
                             ->references('id')
                             ->on('liste_disciplines')
                             ->onDelete('cascade');
            $table->foreign('discipline_similaire_id')
                  ->references('id')
                  ->on('liste_disciplines')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liens_disciplines_similaires', function (Blueprint $table) {

            $table->dropForeign(['discipline_id']);
            $table->dropForeign(['discipline_similaire_id']);

        });
    }
};
