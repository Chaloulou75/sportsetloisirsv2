<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('categories')->delete();

        DB::table('categories')->insert(array(
            0 =>
            array(
                'id' => 30,
                'nom' => 'Pratique encadrée collective',
                'ico' => '',
                'type' => '',
                'diffuseur' => '',
                'derivés' => 'Clubs, cours collectifs, cours à l\'année',
                'ordre' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            1 =>
            array(
                'id' => 32,
                'nom' => 'Pratique encadrée évenementielle',
                'ico' => '',
                'type' => '',
                'diffuseur' => '',
                'derivés' => 'Stages, séjours',
                'ordre' => 30,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            2 =>
            array(
                'id' => 33,
                'nom' => 'Pratique encadrée individuelle',
                'ico' => '',
                'type' => '',
                'diffuseur' => '',
                'derivés' => 'Cours particuliers',
                'ordre' => 10,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            3 =>
            array(
                'id' => 34,
                'nom' => 'Pratique libre au sein d un lieu',
                'ico' => '',
                'type' => '',
                'diffuseur' => '',
                'derivés' => 'Entrée, parcours',
                'ordre' => 20,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            4 =>
            array(
                'id' => 36,
                'nom' => 'Pratique libre ponctuelle hors lieux',
                'ico' => '',
                'type' => '',
                'diffuseur' => '',
                'derivés' => 'Location',
                'ordre' => 50,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            5 =>
            array(
                'id' => 37,
                'nom' => 'Prestations',
                'ico' => '',
                'type' => '',
                'diffuseur' => '',
                'derivés' => 'Ateliers, interventions',
                'ordre' => 60,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            6 =>
            array(
                'id' => 38,
                'nom' => 'Evenements',
                'ico' => '',
                'type' => '',
                'diffuseur' => '',
                'derivés' => 'Spectacles, concerts',
                'ordre' => 70,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            7 =>
            array(
                'id' => 39,
                'nom' => 'Pratique libre événementielle',
                'ico' => '',
                'type' => '',
                'diffuseur' => '',
                'derivés' => 'Compétitions',
                'ordre' => 40,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            8 =>
            array(
                'id' => 40,
                'nom' => 'Achat-vente',
                'ico' => '',
                'type' => '',
                'diffuseur' => '',
                'derivés' => 'Acheter ou vendre un produit',
                'ordre' => 80,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
        ));


    }
}
