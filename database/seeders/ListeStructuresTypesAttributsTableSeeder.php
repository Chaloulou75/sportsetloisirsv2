<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListeStructuresTypesAttributsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('liste_structures_types_attributs')->delete();

        DB::table('liste_structures_types_attributs')->insert(array(
            0 =>
            array(
                'id' => 1,
                'structuretype_id' => 1,
                'nom' => 'numero_licence',
                'type_champ_form' => 'text',
                'created_at' => '2023-04-26 15:38:44',
                'updated_at' => '2023-04-26 15:39:08',
            ),
            1 =>
            array(
                'id' => 2,
                'structuretype_id' => 1,
                'nom' => 'nb_adherents',
                'type_champ_form' => 'select',
                'created_at' => '2023-04-26 15:38:44',
                'updated_at' => '2023-04-26 15:39:08',
            ),
            2 =>
            array(
                'id' => 3,
                'structuretype_id' => 4,
                'nom' => 'type_commerce',
                'type_champ_form' => 'checkbox',
                'created_at' => '2023-04-26 15:38:44',
                'updated_at' => '2023-04-26 15:39:08',
            ),
        ));


    }
}
