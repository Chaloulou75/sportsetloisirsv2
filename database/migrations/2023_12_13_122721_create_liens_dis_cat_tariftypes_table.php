<?php

use App\Models\LienDisciplineCategorie;
use App\Models\ListDiscipline;
use App\Models\ListeTarifType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('liens_dis_cat_tariftypes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discipline_id')->constrained(
                table: 'liste_disciplines',
            )->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('categorie_id')->constrained(
                table: 'liens_disciplines_categories',
            )->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('tarif_type_id')->constrained(
                table: 'liste_tarifs_types',
            )->onUpdate('cascade')->onDelete('cascade');
            $table->string('nom');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liens_dis_cat_tariftypes');
    }
};
