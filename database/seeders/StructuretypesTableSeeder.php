<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StructuretypesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('structuretypes')->delete();

        DB::table('structuretypes')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'Club',
                'slug' => 'club',
                'created_at' => '2023-04-18 14:50:53',
                'updated_at' => '2023-04-18 14:50:53',
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'Coach',
                'slug' => 'coach',
                'created_at' => '2023-04-18 14:50:53',
                'updated_at' => '2023-04-18 14:50:53',
            ),
            2 =>
            array(
                'id' => 3,
                'name' => 'Lieux',
                'slug' => 'lieux',
                'created_at' => '2023-04-18 14:50:53',
                'updated_at' => '2023-04-18 14:50:53',
            ),
            3 =>
            array(
                'id' => 4,
                'name' => 'Agence',
                'slug' => 'agence',
                'created_at' => '2023-04-18 14:50:53',
                'updated_at' => '2023-04-18 14:50:53',
            ),
            4 =>
            array(
                'id' => 5,
                'name' => 'Société',
                'slug' => 'societe',
                'created_at' => '2023-04-18 14:50:53',
                'updated_at' => '2023-04-18 14:50:53',
            ),
        ));


    }
}
