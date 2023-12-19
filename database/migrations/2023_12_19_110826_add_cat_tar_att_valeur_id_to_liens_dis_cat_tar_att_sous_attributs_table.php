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
        Schema::table('liens_dis_cat_tar_att_sous_attributs', function (Blueprint $table) {
            $table->foreignId('att_valeur_id')
            ->nullable()
            ->after('cat_tar_att_id')
            ->constrained(
                table: 'liens_dis_cat_tar_att_valeurs',
            )->onUpdate('cascade')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liens_dis_cat_tar_att_sous_attributs', function (Blueprint $table) {
            $table->dropForeign(['att_valeur_id']);
            $table->dropColumn('att_valeur_id');
        });
    }
};
