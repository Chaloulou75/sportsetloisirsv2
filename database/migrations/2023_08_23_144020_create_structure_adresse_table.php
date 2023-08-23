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
        Schema::create('structure_adresse', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('structure_id')->index('structure_adresse_structure_id_foreign');
            $table->string('address');
            $table->string('zip_code', 20);
            $table->unsignedBigInteger('city_id')->nullable()->index('structure_adresse_city_id_foreign');
            $table->string('city');
            $table->unsignedBigInteger('country_id')->nullable()->index('structure_adresse_country_id_foreign');
            $table->string('country');
            $table->double('address_lat');
            $table->double('address_lng');
            $table->string('phone', 30);
            $table->string('email');
            $table->timestamps();
            $table->string('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('structure_adresse');
    }
};
