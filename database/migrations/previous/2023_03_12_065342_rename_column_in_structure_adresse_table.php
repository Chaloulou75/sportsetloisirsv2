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
        Schema::table('structure_adresse', function (Blueprint $table) {
            $table->renameColumn('ville', 'city');
            $table->renameColumn('pays', 'country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('structure_adresse', function (Blueprint $table) {
            //
        });
    }
};
