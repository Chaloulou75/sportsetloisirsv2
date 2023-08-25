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
        Schema::table('liens_familles_disciplines', function (Blueprint $table) {

            $table->foreign('famille_id')
                    ->references('id')
                    ->on('liste_familles')
                    ->onDelete('cascade');
            $table->foreign('discipline_id')
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
        Schema::table('liens_familles_disciplines', function (Blueprint $table) {

            $table->dropForeign(['famille_id']);
            $table->dropForeign(['discipline_id']);

        });
    }
};
