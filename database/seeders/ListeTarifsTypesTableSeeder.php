<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListeTarifsTypesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('liste_tarifs_types')->delete();

        DB::table('liste_tarifs_types')->insert(array(
            0 =>
            array(
                'id' => 1,
                'type' => 'Licence',
                'slug' => 'licence',
                'created_at' => '2023-05-23 22:58:46',
                'updated_at' => '2023-05-23 22:59:01',
            ),
            1 =>
            array(
                'id' => 2,
                'type' => 'Abonnement / Adhésion',
                'slug' => 'abonnement',
                'created_at' => '2023-05-23 22:58:46',
                'updated_at' => '2023-05-23 22:59:01',
            ),
            2 =>
            array(
                'id' => 3,
                'type' => 'Séance',
                'slug' => 'seance',
                'created_at' => '2023-05-23 22:58:46',
                'updated_at' => '2023-05-23 22:59:01',
            ),
            3 =>
            array(
                'id' => 4,
                'type' => 'Entrée',
                'slug' => 'entrée',
                'created_at' => '2023-05-23 22:58:46',
                'updated_at' => '2023-05-23 22:59:01',
            ),
            4 =>
            array(
                'id' => 5,
                'type' => 'Location',
                'slug' => 'location',
                'created_at' => '2023-05-23 22:58:46',
                'updated_at' => '2023-05-23 22:59:01',
            ),
        ));


    }
}
