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
        Schema::create('activites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('structure_id')->constrained()->onDelete('cascade');
            $table->foreignId('activitetype_id')->constrained()->onDelete('cascade');
            $table->foreignId('discipline_id')->constrained()->onDelete('cascade');
            $table->foreignId('nivel_id')->constrained()->onDelete('cascade');
            $table->foreignId('publictype_id')->constrained()->onDelete('cascade');
            $table->string('address', 150)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('zip_code', 50)->nullable();
            $table->string('country')->nullable();
            $table->double('address_lat')->nullable();
            $table->double('address_lng')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->bigInteger('view_count')->unsigned()->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activites');
    }
};
