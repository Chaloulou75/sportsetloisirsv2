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
                'id' => 1,
                'nom' => 'Cours à l\'année',
                'ico' => '',
                'type' => 'Activité',
                'diffuseur' => 'Clubs',
                'derivés' => 'Adhésions, cours à l\'année, abonnement annuel',
                'ordre' => 1,
            ),
            1 =>
            array(
                'id' => 2,
                'nom' => 'Cours particuliers',
                'ico' => '',
                'type' => 'Activité',
                'diffuseur' => 'Profs',
                'derivés' => 'Cours individuel, cours à domicile, coach',
                'ordre' => 10,
            ),
            2 =>
            array(
                'id' => 3,
                'nom' => 'Cours à la carte',
                'ico' => '',
                'type' => 'Activité',
                'diffuseur' => 'Profs',
                'derivés' => 'Participation ponctuelle',
                'ordre' => 20,
            ),
            3 =>
            array(
                'id' => 4,
                'nom' => 'Stages',
                'ico' => '',
                'type' => 'Activité',
                'diffuseur' => 'Evenements',
                'derivés' => 'Vacances, séjours, stages',
                'ordre' => 51,
            ),
            4 =>
            array(
                'id' => 5,
                'nom' => 'Compétition',
                'ico' => '',
                'type' => 'Activité',
                'diffuseur' => 'Evenements',
                'derivés' => 'Tournois, match',
                'ordre' => 55,
            ),
            5 =>
            array(
                'id' => 6,
                'nom' => 'Pratique libre',
                'ico' => '',
                'type' => 'Activité',
                'diffuseur' => 'Lieux',
                'derivés' => 'Parcours, promenade, entrée, billet',
                'ordre' => 50,
            ),
            6 =>
            array(
                'id' => 7,
                'nom' => 'Sortie accompagnée',
                'ico' => '',
                'type' => 'Activité',
                'diffuseur' => 'Profs',
                'derivés' => 'Guide',
                'ordre' => 40,
            ),
            7 =>
            array(
                'id' => 8,
                'nom' => 'Réservation terrain',
                'ico' => '',
                'type' => 'Activité',
                'diffuseur' => 'Lieux',
                'derivés' => 'Location terrain',
                'ordre' => 22,
            ),
            8 =>
            array(
                'id' => 9,
                'nom' => 'Location matériel',
                'ico' => '',
                'type' => 'Activité',
                'diffuseur' => 'Clubs',
                'derivés' => '',
                'ordre' => 49,
            ),
            9 =>
            array(
                'id' => 10,
                'nom' => 'Parcours',
                'ico' => '',
                'type' => 'Activité',
                'diffuseur' => 'Lieux',
                'derivés' => 'Chemin, promenade, itinéraire',
                'ordre' => 23,
            ),
            10 =>
            array(
                'id' => 11,
                'nom' => 'Prestations',
                'ico' => '',
                'type' => 'Activité',
                'diffuseur' => 'Evenements',
                'derivés' => '',
                'ordre' => 60,
            ),
            11 =>
            array(
                'id' => 12,
                'nom' => 'Séjours',
                'ico' => '',
                'type' => 'Activité',
                'diffuseur' => 'Evenements',
                'derivés' => '',
                'ordre' => 52,
            ),
            12 =>
            array(
                'id' => 13,
                'nom' => 'Entrée',
                'ico' => '',
                'type' => 'Activité',
                'diffuseur' => 'Lieux',
                'derivés' => '',
                'ordre' => 23,
            ),
            13 =>
            array(
                'id' => 14,
                'nom' => 'Cours à distance / Webcam',
                'ico' => '',
                'type' => 'Activité',
                'diffuseur' => 'Profs',
                'derivés' => '',
                'ordre' => 21,
            ),
            14 =>
            array(
                'id' => 15,
                'nom' => 'Clubs',
                'ico' => '',
                'type' => 'Structure',
                'diffuseur' => '',
                'derivés' => '',
                'ordre' => 100,
            ),
            15 =>
            array(
                'id' => 16,
                'nom' => 'Profs',
                'ico' => '',
                'type' => 'Structure',
                'diffuseur' => '',
                'derivés' => '',
                'ordre' => 200,
            ),
            16 =>
            array(
                'id' => 17,
                'nom' => 'Lieux',
                'ico' => '',
                'type' => 'Structure',
                'diffuseur' => '',
                'derivés' => '',
                'ordre' => 300,
            ),
            17 =>
            array(
                'id' => 18,
                'nom' => 'Sociétés',
                'ico' => '',
                'type' => 'Structure',
                'diffuseur' => '',
                'derivés' => '',
                'ordre' => 400,
            ),
            18 =>
            array(
                'id' => 19,
                'nom' => 'Jouer en club',
                'ico' => '',
                'type' => 'Activité',
                'diffuseur' => 'Clubs',
                'derivés' => '',
                'ordre' => 1,
            ),
            19 =>
            array(
                'id' => 20,
                'nom' => 'Abonnement',
                'ico' => '',
                'type' => 'Activité',
                'diffuseur' => 'Lieux',
                'derivés' => '',
                'ordre' => 24,
            ),
            20 =>
            array(
                'id' => 21,
                'nom' => 'Découverte - Initiation',
                'ico' => '',
                'type' => 'Activités',
                'diffuseur' => 'Clubs, Profs, Lieux',
                'derivés' => '',
                'ordre' => 0,
            ),
        ));


    }
}
