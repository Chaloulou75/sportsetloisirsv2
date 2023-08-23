<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('structures_horaires', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('structure_id');
            $table->date('dayopen')->nullable();
            $table->date('dayclose')->nullable();
            $table->time('houropen')->nullable();
            $table->time('hourclose')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('structures_horaires');
    }
};
