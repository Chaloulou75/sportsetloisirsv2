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
        Schema::table('structures_horaires', function (Blueprint $table) {

            $table->foreign('structure_id')
                ->references('id')
                ->on('structures')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('structures_horaires', function (Blueprint $table) {
            $table->dropForeign(['structure_id']);

        });
    }
};
