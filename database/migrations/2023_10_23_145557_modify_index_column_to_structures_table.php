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
        Schema::table('structures', function (Blueprint $table) {
            $table->index('name');
            $table->index('address_lat');
            $table->index('address_lng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('structures', function (Blueprint $table) {
            $table->dropIndex('structures_name_index');
            $table->dropIndex('structures_address_lat_index');
            $table->dropIndex('structures_address_lng_index');
        });
    }
};
