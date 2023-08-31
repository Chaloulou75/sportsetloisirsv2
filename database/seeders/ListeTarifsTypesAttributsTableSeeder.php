<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListeTarifsTypesAttributsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('liste_tarifs_types_attributs')->delete();

        DB::table('liste_tarifs_types_attributs')->insert(array(
            0 =>
            array(
                'id' => 1,
                'type_id' => 1,
                'attribut' => 'Duree',
                'created_at' => '2023-05-23 22:59:16',
                'updated_at' => '2023-05-23 22:59:26',
            ),
            1 =>
            array(
                'id' => 2,
                'type_id' => 2,
                'attribut' => 'Duree',
                'created_at' => '2023-05-23 22:59:38',
                'updated_at' => '2023-05-23 22:59:30',
            ),
            2 =>
            array(
                'id' => 3,
                'type_id' => 3,
                'attribut' => 'Nombre participants',
                'created_at' => '2023-05-23 22:59:35',
                'updated_at' => '2023-05-23 22:59:32',
            ),
            3 =>
            array(
                'id' => 4,
                'type_id' => 4,
                'attribut' => 'Public',
                'created_at' => '2023-05-23 22:59:41',
                'updated_at' => '2023-05-23 23:00:03',
            ),
            4 =>
            array(
                'id' => 5,
                'type_id' => 3,
                'attribut' => 'Duree',
                'created_at' => '2023-05-23 22:59:44',
                'updated_at' => '2023-05-23 23:00:01',
            ),
            5 =>
            array(
                'id' => 6,
                'type_id' => 4,
                'attribut' => 'Nombre participant',
                'created_at' => '2023-05-23 22:59:46',
                'updated_at' => '2023-05-23 22:59:58',
            ),
            6 =>
            array(
                'id' => 7,
                'type_id' => 5,
                'attribut' => 'Duree',
                'created_at' => '2023-05-23 22:59:51',
                'updated_at' => '2023-05-23 22:59:54',
            ),
        ));


    }
}
