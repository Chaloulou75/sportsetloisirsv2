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
            $table->index('address_lat');
            $table->index('address_lng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('structure_adresse', function (Blueprint $table) {
            $table->dropIndex('structure_adresse_address_lat_index');
            $table->dropIndex('structure_adresse_address_lng_index');
        });
    }
};
