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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('ico')->nullable()->change();
            $table->string('type')->nullable()->change();
            $table->string('diffuseur')->nullable()->change();
            $table->text('derives')->nullable()->change();
            $table->integer('ordre')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('ico')->change();
            $table->string('type')->change();
            $table->string('diffuseur')->change();
            $table->text('derives')->change();
            $table->integer('ordre')->change();
        });
    }
};
