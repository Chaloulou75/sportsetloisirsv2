<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListeStructuresTypesValeursTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('liste_structures_types_valeurs')->delete();

        DB::table('liste_structures_types_valeurs')->insert(array(
            0 =>
            array(
                'id' => 1,
                'id_champ' => 2,
                'nom' => 'Plein',
                'created_at' => '2023-04-26 15:39:40',
                'updated_at' => '2023-04-26 15:39:58',
            ),
            1 =>
            array(
                'id' => 2,
                'id_champ' => 2,
                'nom' => 'Pas beaucoup',
                'created_at' => '2023-04-26 15:39:40',
                'updated_at' => '2023-04-26 15:39:58',
            ),
            2 =>
            array(
                'id' => 3,
                'id_champ' => 3,
                'nom' => 'Gros',
                'created_at' => '2023-04-26 15:39:40',
                'updated_at' => '2023-04-26 15:39:58',
            ),
            3 =>
            array(
                'id' => 4,
                'id_champ' => 3,
                'nom' => 'Petit',
                'created_at' => '2023-04-26 15:39:40',
                'updated_at' => '2023-04-26 15:39:58',
            ),
        ));


    }
}
