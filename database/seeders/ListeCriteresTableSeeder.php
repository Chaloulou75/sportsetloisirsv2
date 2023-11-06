<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListeCriteresTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('liste_criteres')->delete();

        DB::table('liste_criteres')->insert(array(
            0 =>
            array(
                'id' => 1,
                'nom' => 'Age',
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            1 =>
            array(
                'id' => 2,
                'nom' => 'Niveau',
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            2 =>
            array(
                'id' => 3,
                'nom' => 'Date',
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            3 =>
            array(
                'id' => 4,
                'nom' => 'Lieux',
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            4 =>
            array(
                'id' => 5,
                'nom' => 'Horaires',
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            5 =>
            array(
                'id' => 6,
                'nom' => 'Horaire',
                'created_at' => '2023-11-02 16:10:41',
                'updated_at' => '2023-11-02 16:10:41',
            ),
            6 =>
            array(
                'id' => 7,
                'nom' => 'Rythme de pratique',
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            7 =>
            array(
                'id' => 8,
                'nom' => 'Durée',
                'created_at' => '2023-10-30 11:52:10',
                'updated_at' => '2023-10-30 11:52:10',
            ),
            8 =>
            array(
                'id' => 9,
                'nom' => 'Rayon',
                'created_at' => '2023-11-02 16:09:41',
                'updated_at' => '2023-11-02 16:09:41',
            ),
            9 =>
            array(
                'id' => 10,
                'nom' => 'Dates',
                'created_at' => '2023-11-02 16:11:03',
                'updated_at' => '2023-11-02 16:11:03',
            ),
            10 =>
            array(
                'id' => 11,
                'nom' => 'Interventions',
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            11 =>
            array(
                'id' => 12,
                'nom' => 'Mois',
                'created_at' => '2023-11-02 16:11:50',
                'updated_at' => '2023-11-02 16:11:50',
            ),
        ));


    }
}
