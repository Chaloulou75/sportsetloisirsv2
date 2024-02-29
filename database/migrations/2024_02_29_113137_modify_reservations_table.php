<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {

            $table->string('session_id')->nullable()->after('id');
            $table->foreignId('user_id')
                ->nullable()
                ->after('session_id')
                ->constrained(table: 'users')
                ->onUpdate('cascade')
                ->nullOnDelete();
            $table->foreignId('discipline_id')
                ->nullable()
                ->after('user_id')
                ->constrained(table: 'liste_disciplines')
                ->onUpdate('cascade')
                ->nullOnDelete();
            $table->foreignId('categorie_id')
                ->nullable()
                ->after('discipline_id')
                ->constrained(table: 'liens_disciplines_categories')
                ->onUpdate('cascade')
                ->nullOnDelete();
            $table->foreignId('structure_id')
                ->nullable()
                ->after('categorie_id')
                ->constrained(table: 'structures')
                ->onUpdate('cascade')
                ->nullOnDelete();
            $table->foreignId('activite_id')
                ->nullable()
                ->after('structure_id')
                ->constrained(table: 'structures_activites')
                ->onUpdate('cascade')
                ->nullOnDelete();
            $table->string('activite_title')->nullable()->after('activite_id');
            $table->foreignId('produit_id')
                ->nullable()
                ->after('activite_title')
                ->constrained(table: 'structures_produits')
                ->onUpdate('cascade')
                ->nullOnDelete();
            $table->json('produit_criteres')->nullable()->after('produit_id');
            $table->foreignId('cat_tarif_id')
                ->nullable()
                ->after('produit_criteres')
                ->constrained(table: 'structures_cat_tarifs')
                ->onUpdate('cascade')
                ->nullOnDelete();
            $table->string('tarif_title')->nullable()->after('cat_tarif_id');
            $table->decimal('tarif_amount', 7)->nullable()->after('tarif_title');
            $table->bigInteger('quantity')->nullable()->after('tarif_amount')->unsigned()->default(1);
            $table->boolean('paid')->after('quantity')->default(false);
            $table->foreignId('user_payeur_id')
                ->nullable()
                ->after('paid')
                ->constrained(table: 'users')
                ->onUpdate('cascade')
                ->nullOnDelete();
            $table->dateTime('paiement_datetime')->nullable()->after('user_payeur_id');
            $table->string('paiement_method')->nullable()->after('paiement_datetime');
            $table->unsignedBigInteger('transaction_number')->nullable()->after('paiement_method');
            $table->boolean('client_confirmed')->nullable()->after('transaction_number');
            $table->dateTime('datetime_client_confirmed')->nullable()->after('client_confirmed');
            $table->boolean('client_cancelled')->nullable()->after('datetime_client_confirmed');
            $table->dateTime('datetime_client_cancelled')->nullable()->after('client_cancelled');
            $table->dateTime('datetime_structure_confirmed')->nullable()->after('confirmed');
            $table->dateTime('datetime_structure_finished')->nullable()->after('finished');
            $table->dateTime('datetime_structure_cancelled')->nullable()->after('cancelled');
            $table->boolean('code_confirmed')->nullable()->after('code');
            $table->dateTime('datetime_code_confirmed')->nullable()->after('code_confirmed');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('session_id');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['discipline_id']);
            $table->dropColumn('discipline_id');
            $table->dropForeign(['categorie_id']);
            $table->dropColumn('categorie_id');
            $table->dropForeign(['structure_id']);
            $table->dropColumn('structure_id');
            $table->dropForeign(['activite_id']);
            $table->dropColumn('activite_id');
            $table->dropColumn('activite_title');
            $table->dropForeign(['produit_id']);
            $table->dropColumn('produit_id');
            $table->dropColumn('produit_criteres');
            $table->dropForeign(['cat_tarif_id']);
            $table->dropColumn('cat_tarif_id');
            $table->dropColumn('tarif_title');
            $table->dropColumn('tarif_amount');
            $table->dropColumn('quantity');
            $table->dropColumn('paid');
            $table->dropForeign(['user_payeur_id']);
            $table->dropColumn('user_payeur_id');
            $table->dropColumn('paiement_datetime');
            $table->dropColumn('paiement_method');
            $table->dropColumn('transaction_number');
            $table->dropColumn('client_confirmed');
            $table->dropColumn('datetime_client_confirmed');
            $table->dropColumn('client_cancelled');
            $table->dropColumn('datetime_client_cancelled');
            $table->dropColumn('datetime_structure_confirmed');
            $table->dropColumn('datetime_structure_finished');
            $table->dropColumn('datetime_structure_cancelled');
            $table->dropColumn('code_confirmed');
            $table->dropColumn('datetime_code_confirmed');
        });
    }
};
