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
        Schema::create('structures_tarifs_types_infos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('structure_id');
            $table->integer('tarif_id');
            $table->integer('type_id');
            $table->integer('attribut_id');
            $table->string('valeur');
            $table->string('unite')->nullable();
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
        Schema::dropIfExists('structures_tarifs_types_infos');
    }
};
