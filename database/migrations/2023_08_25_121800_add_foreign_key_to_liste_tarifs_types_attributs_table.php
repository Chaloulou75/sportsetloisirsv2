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
        Schema::table('liste_tarifs_types_attributs', function (Blueprint $table) {

            $table->foreign('type_id')
                ->references('id')
                ->on('liste_tarifs_types')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liste_tarifs_types_attributs', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
        });
    }
};
