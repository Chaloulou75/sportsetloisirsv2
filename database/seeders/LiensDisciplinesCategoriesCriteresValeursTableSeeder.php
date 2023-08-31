<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LiensDisciplinesCategoriesCriteresValeursTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('liens_disciplines_categories_criteres_valeurs')->delete();

        DB::table('liens_disciplines_categories_criteres_valeurs')->insert(array(
            0 =>
            array(
                'id' => 33,
                'discipline_categorie_critere_id' => 9,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            1 =>
            array(
                'id' => 34,
                'discipline_categorie_critere_id' => 9,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            2 =>
            array(
                'id' => 35,
                'discipline_categorie_critere_id' => 9,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            3 =>
            array(
                'id' => 36,
                'discipline_categorie_critere_id' => 9,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            4 =>
            array(
                'id' => 37,
                'discipline_categorie_critere_id' => 10,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            5 =>
            array(
                'id' => 38,
                'discipline_categorie_critere_id' => 10,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            6 =>
            array(
                'id' => 39,
                'discipline_categorie_critere_id' => 10,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            7 =>
            array(
                'id' => 40,
                'discipline_categorie_critere_id' => 10,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            8 =>
            array(
                'id' => 41,
                'discipline_categorie_critere_id' => 10,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            9 =>
            array(
                'id' => 42,
                'discipline_categorie_critere_id' => 11,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            10 =>
            array(
                'id' => 43,
                'discipline_categorie_critere_id' => 11,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            11 =>
            array(
                'id' => 44,
                'discipline_categorie_critere_id' => 11,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            12 =>
            array(
                'id' => 45,
                'discipline_categorie_critere_id' => 12,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            13 =>
            array(
                'id' => 46,
                'discipline_categorie_critere_id' => 12,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            14 =>
            array(
                'id' => 47,
                'discipline_categorie_critere_id' => 12,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            15 =>
            array(
                'id' => 48,
                'discipline_categorie_critere_id' => 12,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            16 =>
            array(
                'id' => 49,
                'discipline_categorie_critere_id' => 13,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            17 =>
            array(
                'id' => 50,
                'discipline_categorie_critere_id' => 13,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            18 =>
            array(
                'id' => 51,
                'discipline_categorie_critere_id' => 13,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            19 =>
            array(
                'id' => 52,
                'discipline_categorie_critere_id' => 13,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            20 =>
            array(
                'id' => 53,
                'discipline_categorie_critere_id' => 13,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            21 =>
            array(
                'id' => 54,
                'discipline_categorie_critere_id' => 14,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            22 =>
            array(
                'id' => 55,
                'discipline_categorie_critere_id' => 14,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            23 =>
            array(
                'id' => 56,
                'discipline_categorie_critere_id' => 14,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            24 =>
            array(
                'id' => 57,
                'discipline_categorie_critere_id' => 14,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            25 =>
            array(
                'id' => 58,
                'discipline_categorie_critere_id' => 15,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            26 =>
            array(
                'id' => 59,
                'discipline_categorie_critere_id' => 15,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            27 =>
            array(
                'id' => 60,
                'discipline_categorie_critere_id' => 15,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            28 =>
            array(
                'id' => 61,
                'discipline_categorie_critere_id' => 15,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            29 =>
            array(
                'id' => 62,
                'discipline_categorie_critere_id' => 15,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            30 =>
            array(
                'id' => 63,
                'discipline_categorie_critere_id' => 16,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            31 =>
            array(
                'id' => 64,
                'discipline_categorie_critere_id' => 16,
                'valeur' => 'E-learning',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            32 =>
            array(
                'id' => 65,
                'discipline_categorie_critere_id' => 16,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            33 =>
            array(
                'id' => 66,
                'discipline_categorie_critere_id' => 17,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            34 =>
            array(
                'id' => 67,
                'discipline_categorie_critere_id' => 17,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            35 =>
            array(
                'id' => 68,
                'discipline_categorie_critere_id' => 17,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            36 =>
            array(
                'id' => 69,
                'discipline_categorie_critere_id' => 17,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            37 =>
            array(
                'id' => 70,
                'discipline_categorie_critere_id' => 18,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            38 =>
            array(
                'id' => 71,
                'discipline_categorie_critere_id' => 18,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            39 =>
            array(
                'id' => 72,
                'discipline_categorie_critere_id' => 18,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            40 =>
            array(
                'id' => 73,
                'discipline_categorie_critere_id' => 18,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            41 =>
            array(
                'id' => 74,
                'discipline_categorie_critere_id' => 18,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            42 =>
            array(
                'id' => 75,
                'discipline_categorie_critere_id' => 19,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            43 =>
            array(
                'id' => 76,
                'discipline_categorie_critere_id' => 19,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            44 =>
            array(
                'id' => 77,
                'discipline_categorie_critere_id' => 19,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            45 =>
            array(
                'id' => 78,
                'discipline_categorie_critere_id' => 20,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            46 =>
            array(
                'id' => 79,
                'discipline_categorie_critere_id' => 20,
                'valeur' => 'E-learning',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            47 =>
            array(
                'id' => 80,
                'discipline_categorie_critere_id' => 20,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            48 =>
            array(
                'id' => 81,
                'discipline_categorie_critere_id' => 21,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            49 =>
            array(
                'id' => 82,
                'discipline_categorie_critere_id' => 21,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            50 =>
            array(
                'id' => 83,
                'discipline_categorie_critere_id' => 21,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            51 =>
            array(
                'id' => 84,
                'discipline_categorie_critere_id' => 21,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            52 =>
            array(
                'id' => 85,
                'discipline_categorie_critere_id' => 22,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            53 =>
            array(
                'id' => 86,
                'discipline_categorie_critere_id' => 22,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            54 =>
            array(
                'id' => 87,
                'discipline_categorie_critere_id' => 23,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            55 =>
            array(
                'id' => 88,
                'discipline_categorie_critere_id' => 23,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            56 =>
            array(
                'id' => 89,
                'discipline_categorie_critere_id' => 23,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            57 =>
            array(
                'id' => 90,
                'discipline_categorie_critere_id' => 23,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            58 =>
            array(
                'id' => 91,
                'discipline_categorie_critere_id' => 24,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            59 =>
            array(
                'id' => 92,
                'discipline_categorie_critere_id' => 24,
                'valeur' => 'E-learning',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            60 =>
            array(
                'id' => 93,
                'discipline_categorie_critere_id' => 24,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            61 =>
            array(
                'id' => 94,
                'discipline_categorie_critere_id' => 25,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            62 =>
            array(
                'id' => 95,
                'discipline_categorie_critere_id' => 25,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            63 =>
            array(
                'id' => 96,
                'discipline_categorie_critere_id' => 25,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            64 =>
            array(
                'id' => 97,
                'discipline_categorie_critere_id' => 25,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            65 =>
            array(
                'id' => 98,
                'discipline_categorie_critere_id' => 26,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            66 =>
            array(
                'id' => 99,
                'discipline_categorie_critere_id' => 26,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            67 =>
            array(
                'id' => 100,
                'discipline_categorie_critere_id' => 26,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            68 =>
            array(
                'id' => 101,
                'discipline_categorie_critere_id' => 26,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            69 =>
            array(
                'id' => 102,
                'discipline_categorie_critere_id' => 26,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            70 =>
            array(
                'id' => 103,
                'discipline_categorie_critere_id' => 27,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            71 =>
            array(
                'id' => 104,
                'discipline_categorie_critere_id' => 27,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            72 =>
            array(
                'id' => 105,
                'discipline_categorie_critere_id' => 27,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            73 =>
            array(
                'id' => 106,
                'discipline_categorie_critere_id' => 28,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            74 =>
            array(
                'id' => 107,
                'discipline_categorie_critere_id' => 28,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            75 =>
            array(
                'id' => 108,
                'discipline_categorie_critere_id' => 28,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            76 =>
            array(
                'id' => 109,
                'discipline_categorie_critere_id' => 28,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            77 =>
            array(
                'id' => 110,
                'discipline_categorie_critere_id' => 29,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            78 =>
            array(
                'id' => 111,
                'discipline_categorie_critere_id' => 29,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            79 =>
            array(
                'id' => 112,
                'discipline_categorie_critere_id' => 29,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            80 =>
            array(
                'id' => 113,
                'discipline_categorie_critere_id' => 29,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            81 =>
            array(
                'id' => 114,
                'discipline_categorie_critere_id' => 29,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            82 =>
            array(
                'id' => 115,
                'discipline_categorie_critere_id' => 30,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            83 =>
            array(
                'id' => 116,
                'discipline_categorie_critere_id' => 30,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            84 =>
            array(
                'id' => 117,
                'discipline_categorie_critere_id' => 30,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            85 =>
            array(
                'id' => 118,
                'discipline_categorie_critere_id' => 30,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            86 =>
            array(
                'id' => 119,
                'discipline_categorie_critere_id' => 31,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            87 =>
            array(
                'id' => 120,
                'discipline_categorie_critere_id' => 31,
                'valeur' => 'E-learning',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            88 =>
            array(
                'id' => 121,
                'discipline_categorie_critere_id' => 31,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            89 =>
            array(
                'id' => 122,
                'discipline_categorie_critere_id' => 32,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            90 =>
            array(
                'id' => 123,
                'discipline_categorie_critere_id' => 32,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            91 =>
            array(
                'id' => 124,
                'discipline_categorie_critere_id' => 32,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            92 =>
            array(
                'id' => 125,
                'discipline_categorie_critere_id' => 32,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            93 =>
            array(
                'id' => 126,
                'discipline_categorie_critere_id' => 33,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            94 =>
            array(
                'id' => 127,
                'discipline_categorie_critere_id' => 33,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            95 =>
            array(
                'id' => 128,
                'discipline_categorie_critere_id' => 33,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            96 =>
            array(
                'id' => 129,
                'discipline_categorie_critere_id' => 33,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            97 =>
            array(
                'id' => 130,
                'discipline_categorie_critere_id' => 33,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            98 =>
            array(
                'id' => 131,
                'discipline_categorie_critere_id' => 34,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            99 =>
            array(
                'id' => 132,
                'discipline_categorie_critere_id' => 34,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            100 =>
            array(
                'id' => 133,
                'discipline_categorie_critere_id' => 34,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            101 =>
            array(
                'id' => 134,
                'discipline_categorie_critere_id' => 34,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            102 =>
            array(
                'id' => 135,
                'discipline_categorie_critere_id' => 35,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            103 =>
            array(
                'id' => 136,
                'discipline_categorie_critere_id' => 35,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            104 =>
            array(
                'id' => 137,
                'discipline_categorie_critere_id' => 35,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            105 =>
            array(
                'id' => 138,
                'discipline_categorie_critere_id' => 35,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            106 =>
            array(
                'id' => 139,
                'discipline_categorie_critere_id' => 35,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            107 =>
            array(
                'id' => 140,
                'discipline_categorie_critere_id' => 36,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            108 =>
            array(
                'id' => 141,
                'discipline_categorie_critere_id' => 36,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            109 =>
            array(
                'id' => 142,
                'discipline_categorie_critere_id' => 36,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            110 =>
            array(
                'id' => 143,
                'discipline_categorie_critere_id' => 37,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            111 =>
            array(
                'id' => 144,
                'discipline_categorie_critere_id' => 37,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            112 =>
            array(
                'id' => 145,
                'discipline_categorie_critere_id' => 37,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            113 =>
            array(
                'id' => 146,
                'discipline_categorie_critere_id' => 37,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            114 =>
            array(
                'id' => 147,
                'discipline_categorie_critere_id' => 38,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            115 =>
            array(
                'id' => 148,
                'discipline_categorie_critere_id' => 38,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            116 =>
            array(
                'id' => 149,
                'discipline_categorie_critere_id' => 38,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            117 =>
            array(
                'id' => 150,
                'discipline_categorie_critere_id' => 38,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            118 =>
            array(
                'id' => 151,
                'discipline_categorie_critere_id' => 38,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            119 =>
            array(
                'id' => 152,
                'discipline_categorie_critere_id' => 39,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            120 =>
            array(
                'id' => 153,
                'discipline_categorie_critere_id' => 39,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            121 =>
            array(
                'id' => 154,
                'discipline_categorie_critere_id' => 39,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            122 =>
            array(
                'id' => 155,
                'discipline_categorie_critere_id' => 39,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            123 =>
            array(
                'id' => 156,
                'discipline_categorie_critere_id' => 40,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            124 =>
            array(
                'id' => 157,
                'discipline_categorie_critere_id' => 40,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            125 =>
            array(
                'id' => 158,
                'discipline_categorie_critere_id' => 40,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            126 =>
            array(
                'id' => 159,
                'discipline_categorie_critere_id' => 40,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            127 =>
            array(
                'id' => 160,
                'discipline_categorie_critere_id' => 40,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            128 =>
            array(
                'id' => 161,
                'discipline_categorie_critere_id' => 41,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            129 =>
            array(
                'id' => 162,
                'discipline_categorie_critere_id' => 41,
                'valeur' => 'E-learning',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            130 =>
            array(
                'id' => 163,
                'discipline_categorie_critere_id' => 41,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            131 =>
            array(
                'id' => 164,
                'discipline_categorie_critere_id' => 42,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            132 =>
            array(
                'id' => 165,
                'discipline_categorie_critere_id' => 42,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            133 =>
            array(
                'id' => 166,
                'discipline_categorie_critere_id' => 42,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            134 =>
            array(
                'id' => 167,
                'discipline_categorie_critere_id' => 42,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            135 =>
            array(
                'id' => 168,
                'discipline_categorie_critere_id' => 43,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            136 =>
            array(
                'id' => 169,
                'discipline_categorie_critere_id' => 43,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            137 =>
            array(
                'id' => 170,
                'discipline_categorie_critere_id' => 43,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            138 =>
            array(
                'id' => 171,
                'discipline_categorie_critere_id' => 43,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            139 =>
            array(
                'id' => 172,
                'discipline_categorie_critere_id' => 44,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            140 =>
            array(
                'id' => 173,
                'discipline_categorie_critere_id' => 44,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            141 =>
            array(
                'id' => 174,
                'discipline_categorie_critere_id' => 44,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            142 =>
            array(
                'id' => 175,
                'discipline_categorie_critere_id' => 44,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            143 =>
            array(
                'id' => 176,
                'discipline_categorie_critere_id' => 44,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            144 =>
            array(
                'id' => 177,
                'discipline_categorie_critere_id' => 45,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            145 =>
            array(
                'id' => 178,
                'discipline_categorie_critere_id' => 45,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            146 =>
            array(
                'id' => 179,
                'discipline_categorie_critere_id' => 45,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            147 =>
            array(
                'id' => 180,
                'discipline_categorie_critere_id' => 46,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            148 =>
            array(
                'id' => 181,
                'discipline_categorie_critere_id' => 46,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            149 =>
            array(
                'id' => 182,
                'discipline_categorie_critere_id' => 46,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            150 =>
            array(
                'id' => 183,
                'discipline_categorie_critere_id' => 46,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            151 =>
            array(
                'id' => 184,
                'discipline_categorie_critere_id' => 47,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            152 =>
            array(
                'id' => 185,
                'discipline_categorie_critere_id' => 47,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            153 =>
            array(
                'id' => 186,
                'discipline_categorie_critere_id' => 47,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            154 =>
            array(
                'id' => 187,
                'discipline_categorie_critere_id' => 47,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            155 =>
            array(
                'id' => 188,
                'discipline_categorie_critere_id' => 47,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            156 =>
            array(
                'id' => 189,
                'discipline_categorie_critere_id' => 48,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            157 =>
            array(
                'id' => 190,
                'discipline_categorie_critere_id' => 48,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            158 =>
            array(
                'id' => 191,
                'discipline_categorie_critere_id' => 49,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            159 =>
            array(
                'id' => 192,
                'discipline_categorie_critere_id' => 49,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            160 =>
            array(
                'id' => 193,
                'discipline_categorie_critere_id' => 49,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            161 =>
            array(
                'id' => 194,
                'discipline_categorie_critere_id' => 49,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            162 =>
            array(
                'id' => 195,
                'discipline_categorie_critere_id' => 50,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            163 =>
            array(
                'id' => 196,
                'discipline_categorie_critere_id' => 50,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            164 =>
            array(
                'id' => 197,
                'discipline_categorie_critere_id' => 50,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            165 =>
            array(
                'id' => 198,
                'discipline_categorie_critere_id' => 51,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            166 =>
            array(
                'id' => 199,
                'discipline_categorie_critere_id' => 51,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            167 =>
            array(
                'id' => 200,
                'discipline_categorie_critere_id' => 52,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            168 =>
            array(
                'id' => 201,
                'discipline_categorie_critere_id' => 52,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            169 =>
            array(
                'id' => 202,
                'discipline_categorie_critere_id' => 52,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            170 =>
            array(
                'id' => 203,
                'discipline_categorie_critere_id' => 53,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            171 =>
            array(
                'id' => 204,
                'discipline_categorie_critere_id' => 53,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            172 =>
            array(
                'id' => 205,
                'discipline_categorie_critere_id' => 53,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            173 =>
            array(
                'id' => 206,
                'discipline_categorie_critere_id' => 54,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            174 =>
            array(
                'id' => 207,
                'discipline_categorie_critere_id' => 54,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            175 =>
            array(
                'id' => 208,
                'discipline_categorie_critere_id' => 55,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            176 =>
            array(
                'id' => 209,
                'discipline_categorie_critere_id' => 55,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            177 =>
            array(
                'id' => 210,
                'discipline_categorie_critere_id' => 55,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            178 =>
            array(
                'id' => 211,
                'discipline_categorie_critere_id' => 55,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            179 =>
            array(
                'id' => 212,
                'discipline_categorie_critere_id' => 56,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            180 =>
            array(
                'id' => 213,
                'discipline_categorie_critere_id' => 56,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            181 =>
            array(
                'id' => 214,
                'discipline_categorie_critere_id' => 56,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            182 =>
            array(
                'id' => 215,
                'discipline_categorie_critere_id' => 56,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            183 =>
            array(
                'id' => 216,
                'discipline_categorie_critere_id' => 57,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            184 =>
            array(
                'id' => 217,
                'discipline_categorie_critere_id' => 57,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            185 =>
            array(
                'id' => 218,
                'discipline_categorie_critere_id' => 57,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            186 =>
            array(
                'id' => 219,
                'discipline_categorie_critere_id' => 58,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            187 =>
            array(
                'id' => 220,
                'discipline_categorie_critere_id' => 58,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            188 =>
            array(
                'id' => 221,
                'discipline_categorie_critere_id' => 58,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            189 =>
            array(
                'id' => 222,
                'discipline_categorie_critere_id' => 58,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            190 =>
            array(
                'id' => 223,
                'discipline_categorie_critere_id' => 59,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            191 =>
            array(
                'id' => 224,
                'discipline_categorie_critere_id' => 59,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            192 =>
            array(
                'id' => 225,
                'discipline_categorie_critere_id' => 59,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            193 =>
            array(
                'id' => 226,
                'discipline_categorie_critere_id' => 59,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            194 =>
            array(
                'id' => 227,
                'discipline_categorie_critere_id' => 59,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            195 =>
            array(
                'id' => 228,
                'discipline_categorie_critere_id' => 60,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            196 =>
            array(
                'id' => 229,
                'discipline_categorie_critere_id' => 60,
                'valeur' => 'E-learning',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            197 =>
            array(
                'id' => 230,
                'discipline_categorie_critere_id' => 60,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            198 =>
            array(
                'id' => 231,
                'discipline_categorie_critere_id' => 61,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            199 =>
            array(
                'id' => 232,
                'discipline_categorie_critere_id' => 61,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            200 =>
            array(
                'id' => 233,
                'discipline_categorie_critere_id' => 61,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            201 =>
            array(
                'id' => 234,
                'discipline_categorie_critere_id' => 61,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            202 =>
            array(
                'id' => 235,
                'discipline_categorie_critere_id' => 62,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            203 =>
            array(
                'id' => 236,
                'discipline_categorie_critere_id' => 62,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            204 =>
            array(
                'id' => 237,
                'discipline_categorie_critere_id' => 62,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            205 =>
            array(
                'id' => 238,
                'discipline_categorie_critere_id' => 62,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            206 =>
            array(
                'id' => 239,
                'discipline_categorie_critere_id' => 63,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            207 =>
            array(
                'id' => 240,
                'discipline_categorie_critere_id' => 63,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            208 =>
            array(
                'id' => 241,
                'discipline_categorie_critere_id' => 63,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            209 =>
            array(
                'id' => 242,
                'discipline_categorie_critere_id' => 63,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            210 =>
            array(
                'id' => 243,
                'discipline_categorie_critere_id' => 63,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            211 =>
            array(
                'id' => 244,
                'discipline_categorie_critere_id' => 64,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            212 =>
            array(
                'id' => 245,
                'discipline_categorie_critere_id' => 64,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            213 =>
            array(
                'id' => 246,
                'discipline_categorie_critere_id' => 64,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            214 =>
            array(
                'id' => 247,
                'discipline_categorie_critere_id' => 65,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            215 =>
            array(
                'id' => 248,
                'discipline_categorie_critere_id' => 65,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            216 =>
            array(
                'id' => 249,
                'discipline_categorie_critere_id' => 65,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            217 =>
            array(
                'id' => 250,
                'discipline_categorie_critere_id' => 65,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            218 =>
            array(
                'id' => 251,
                'discipline_categorie_critere_id' => 66,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            219 =>
            array(
                'id' => 252,
                'discipline_categorie_critere_id' => 66,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            220 =>
            array(
                'id' => 253,
                'discipline_categorie_critere_id' => 66,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            221 =>
            array(
                'id' => 254,
                'discipline_categorie_critere_id' => 66,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            222 =>
            array(
                'id' => 255,
                'discipline_categorie_critere_id' => 66,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            223 =>
            array(
                'id' => 256,
                'discipline_categorie_critere_id' => 67,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            224 =>
            array(
                'id' => 257,
                'discipline_categorie_critere_id' => 67,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            225 =>
            array(
                'id' => 258,
                'discipline_categorie_critere_id' => 67,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            226 =>
            array(
                'id' => 259,
                'discipline_categorie_critere_id' => 67,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            227 =>
            array(
                'id' => 260,
                'discipline_categorie_critere_id' => 68,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            228 =>
            array(
                'id' => 261,
                'discipline_categorie_critere_id' => 68,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            229 =>
            array(
                'id' => 262,
                'discipline_categorie_critere_id' => 68,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            230 =>
            array(
                'id' => 263,
                'discipline_categorie_critere_id' => 68,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            231 =>
            array(
                'id' => 264,
                'discipline_categorie_critere_id' => 68,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            232 =>
            array(
                'id' => 265,
                'discipline_categorie_critere_id' => 69,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            233 =>
            array(
                'id' => 266,
                'discipline_categorie_critere_id' => 70,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            234 =>
            array(
                'id' => 267,
                'discipline_categorie_critere_id' => 70,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            235 =>
            array(
                'id' => 268,
                'discipline_categorie_critere_id' => 70,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            236 =>
            array(
                'id' => 269,
                'discipline_categorie_critere_id' => 70,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            237 =>
            array(
                'id' => 270,
                'discipline_categorie_critere_id' => 71,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            238 =>
            array(
                'id' => 271,
                'discipline_categorie_critere_id' => 71,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            239 =>
            array(
                'id' => 272,
                'discipline_categorie_critere_id' => 71,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            240 =>
            array(
                'id' => 273,
                'discipline_categorie_critere_id' => 71,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            241 =>
            array(
                'id' => 274,
                'discipline_categorie_critere_id' => 71,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            242 =>
            array(
                'id' => 275,
                'discipline_categorie_critere_id' => 72,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            243 =>
            array(
                'id' => 276,
                'discipline_categorie_critere_id' => 72,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            244 =>
            array(
                'id' => 277,
                'discipline_categorie_critere_id' => 72,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            245 =>
            array(
                'id' => 278,
                'discipline_categorie_critere_id' => 72,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            246 =>
            array(
                'id' => 279,
                'discipline_categorie_critere_id' => 72,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            247 =>
            array(
                'id' => 280,
                'discipline_categorie_critere_id' => 73,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            248 =>
            array(
                'id' => 281,
                'discipline_categorie_critere_id' => 73,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            249 =>
            array(
                'id' => 282,
                'discipline_categorie_critere_id' => 73,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            250 =>
            array(
                'id' => 283,
                'discipline_categorie_critere_id' => 74,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            251 =>
            array(
                'id' => 284,
                'discipline_categorie_critere_id' => 74,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            252 =>
            array(
                'id' => 285,
                'discipline_categorie_critere_id' => 74,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            253 =>
            array(
                'id' => 286,
                'discipline_categorie_critere_id' => 74,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            254 =>
            array(
                'id' => 287,
                'discipline_categorie_critere_id' => 74,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            255 =>
            array(
                'id' => 288,
                'discipline_categorie_critere_id' => 75,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            256 =>
            array(
                'id' => 289,
                'discipline_categorie_critere_id' => 75,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            257 =>
            array(
                'id' => 290,
                'discipline_categorie_critere_id' => 75,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            258 =>
            array(
                'id' => 291,
                'discipline_categorie_critere_id' => 75,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            259 =>
            array(
                'id' => 292,
                'discipline_categorie_critere_id' => 75,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            260 =>
            array(
                'id' => 293,
                'discipline_categorie_critere_id' => 76,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            261 =>
            array(
                'id' => 294,
                'discipline_categorie_critere_id' => 77,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            262 =>
            array(
                'id' => 295,
                'discipline_categorie_critere_id' => 77,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            263 =>
            array(
                'id' => 296,
                'discipline_categorie_critere_id' => 77,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            264 =>
            array(
                'id' => 297,
                'discipline_categorie_critere_id' => 77,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            265 =>
            array(
                'id' => 298,
                'discipline_categorie_critere_id' => 78,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            266 =>
            array(
                'id' => 299,
                'discipline_categorie_critere_id' => 78,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            267 =>
            array(
                'id' => 300,
                'discipline_categorie_critere_id' => 78,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            268 =>
            array(
                'id' => 301,
                'discipline_categorie_critere_id' => 78,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            269 =>
            array(
                'id' => 302,
                'discipline_categorie_critere_id' => 78,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            270 =>
            array(
                'id' => 303,
                'discipline_categorie_critere_id' => 79,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            271 =>
            array(
                'id' => 304,
                'discipline_categorie_critere_id' => 79,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            272 =>
            array(
                'id' => 305,
                'discipline_categorie_critere_id' => 79,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            273 =>
            array(
                'id' => 306,
                'discipline_categorie_critere_id' => 80,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            274 =>
            array(
                'id' => 307,
                'discipline_categorie_critere_id' => 80,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            275 =>
            array(
                'id' => 308,
                'discipline_categorie_critere_id' => 80,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            276 =>
            array(
                'id' => 309,
                'discipline_categorie_critere_id' => 80,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            277 =>
            array(
                'id' => 310,
                'discipline_categorie_critere_id' => 81,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            278 =>
            array(
                'id' => 311,
                'discipline_categorie_critere_id' => 81,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            279 =>
            array(
                'id' => 312,
                'discipline_categorie_critere_id' => 81,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            280 =>
            array(
                'id' => 313,
                'discipline_categorie_critere_id' => 81,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            281 =>
            array(
                'id' => 314,
                'discipline_categorie_critere_id' => 81,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            282 =>
            array(
                'id' => 315,
                'discipline_categorie_critere_id' => 82,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            283 =>
            array(
                'id' => 316,
                'discipline_categorie_critere_id' => 82,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            284 =>
            array(
                'id' => 317,
                'discipline_categorie_critere_id' => 82,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            285 =>
            array(
                'id' => 318,
                'discipline_categorie_critere_id' => 82,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            286 =>
            array(
                'id' => 319,
                'discipline_categorie_critere_id' => 83,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            287 =>
            array(
                'id' => 320,
                'discipline_categorie_critere_id' => 83,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            288 =>
            array(
                'id' => 321,
                'discipline_categorie_critere_id' => 84,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            289 =>
            array(
                'id' => 322,
                'discipline_categorie_critere_id' => 84,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            290 =>
            array(
                'id' => 323,
                'discipline_categorie_critere_id' => 84,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            291 =>
            array(
                'id' => 324,
                'discipline_categorie_critere_id' => 84,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            292 =>
            array(
                'id' => 325,
                'discipline_categorie_critere_id' => 85,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            293 =>
            array(
                'id' => 326,
                'discipline_categorie_critere_id' => 85,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            294 =>
            array(
                'id' => 327,
                'discipline_categorie_critere_id' => 85,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            295 =>
            array(
                'id' => 328,
                'discipline_categorie_critere_id' => 85,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            296 =>
            array(
                'id' => 329,
                'discipline_categorie_critere_id' => 86,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            297 =>
            array(
                'id' => 330,
                'discipline_categorie_critere_id' => 86,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            298 =>
            array(
                'id' => 331,
                'discipline_categorie_critere_id' => 86,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            299 =>
            array(
                'id' => 332,
                'discipline_categorie_critere_id' => 86,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            300 =>
            array(
                'id' => 333,
                'discipline_categorie_critere_id' => 86,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            301 =>
            array(
                'id' => 334,
                'discipline_categorie_critere_id' => 87,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            302 =>
            array(
                'id' => 335,
                'discipline_categorie_critere_id' => 87,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            303 =>
            array(
                'id' => 336,
                'discipline_categorie_critere_id' => 87,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            304 =>
            array(
                'id' => 337,
                'discipline_categorie_critere_id' => 88,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            305 =>
            array(
                'id' => 338,
                'discipline_categorie_critere_id' => 88,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            306 =>
            array(
                'id' => 339,
                'discipline_categorie_critere_id' => 89,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            307 =>
            array(
                'id' => 340,
                'discipline_categorie_critere_id' => 89,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            308 =>
            array(
                'id' => 341,
                'discipline_categorie_critere_id' => 89,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            309 =>
            array(
                'id' => 342,
                'discipline_categorie_critere_id' => 89,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            310 =>
            array(
                'id' => 343,
                'discipline_categorie_critere_id' => 90,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            311 =>
            array(
                'id' => 344,
                'discipline_categorie_critere_id' => 90,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            312 =>
            array(
                'id' => 345,
                'discipline_categorie_critere_id' => 90,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            313 =>
            array(
                'id' => 346,
                'discipline_categorie_critere_id' => 90,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            314 =>
            array(
                'id' => 347,
                'discipline_categorie_critere_id' => 90,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            315 =>
            array(
                'id' => 348,
                'discipline_categorie_critere_id' => 91,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            316 =>
            array(
                'id' => 349,
                'discipline_categorie_critere_id' => 91,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            317 =>
            array(
                'id' => 350,
                'discipline_categorie_critere_id' => 91,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            318 =>
            array(
                'id' => 351,
                'discipline_categorie_critere_id' => 92,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            319 =>
            array(
                'id' => 352,
                'discipline_categorie_critere_id' => 92,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            320 =>
            array(
                'id' => 353,
                'discipline_categorie_critere_id' => 92,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            321 =>
            array(
                'id' => 354,
                'discipline_categorie_critere_id' => 92,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            322 =>
            array(
                'id' => 355,
                'discipline_categorie_critere_id' => 94,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            323 =>
            array(
                'id' => 356,
                'discipline_categorie_critere_id' => 94,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            324 =>
            array(
                'id' => 357,
                'discipline_categorie_critere_id' => 94,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            325 =>
            array(
                'id' => 358,
                'discipline_categorie_critere_id' => 94,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            326 =>
            array(
                'id' => 359,
                'discipline_categorie_critere_id' => 95,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            327 =>
            array(
                'id' => 360,
                'discipline_categorie_critere_id' => 95,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            328 =>
            array(
                'id' => 361,
                'discipline_categorie_critere_id' => 95,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            329 =>
            array(
                'id' => 362,
                'discipline_categorie_critere_id' => 95,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            330 =>
            array(
                'id' => 363,
                'discipline_categorie_critere_id' => 95,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            331 =>
            array(
                'id' => 364,
                'discipline_categorie_critere_id' => 96,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            332 =>
            array(
                'id' => 365,
                'discipline_categorie_critere_id' => 97,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            333 =>
            array(
                'id' => 366,
                'discipline_categorie_critere_id' => 97,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            334 =>
            array(
                'id' => 367,
                'discipline_categorie_critere_id' => 98,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            335 =>
            array(
                'id' => 368,
                'discipline_categorie_critere_id' => 98,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            336 =>
            array(
                'id' => 369,
                'discipline_categorie_critere_id' => 98,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            337 =>
            array(
                'id' => 370,
                'discipline_categorie_critere_id' => 98,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            338 =>
            array(
                'id' => 371,
                'discipline_categorie_critere_id' => 99,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            339 =>
            array(
                'id' => 372,
                'discipline_categorie_critere_id' => 99,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            340 =>
            array(
                'id' => 373,
                'discipline_categorie_critere_id' => 99,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            341 =>
            array(
                'id' => 374,
                'discipline_categorie_critere_id' => 99,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            342 =>
            array(
                'id' => 375,
                'discipline_categorie_critere_id' => 99,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            343 =>
            array(
                'id' => 376,
                'discipline_categorie_critere_id' => 100,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            344 =>
            array(
                'id' => 377,
                'discipline_categorie_critere_id' => 100,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            345 =>
            array(
                'id' => 378,
                'discipline_categorie_critere_id' => 100,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            346 =>
            array(
                'id' => 379,
                'discipline_categorie_critere_id' => 102,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            347 =>
            array(
                'id' => 380,
                'discipline_categorie_critere_id' => 102,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            348 =>
            array(
                'id' => 381,
                'discipline_categorie_critere_id' => 102,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            349 =>
            array(
                'id' => 382,
                'discipline_categorie_critere_id' => 103,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            350 =>
            array(
                'id' => 383,
                'discipline_categorie_critere_id' => 103,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            351 =>
            array(
                'id' => 384,
                'discipline_categorie_critere_id' => 103,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            352 =>
            array(
                'id' => 385,
                'discipline_categorie_critere_id' => 103,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            353 =>
            array(
                'id' => 386,
                'discipline_categorie_critere_id' => 104,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            354 =>
            array(
                'id' => 387,
                'discipline_categorie_critere_id' => 104,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            355 =>
            array(
                'id' => 388,
                'discipline_categorie_critere_id' => 104,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            356 =>
            array(
                'id' => 389,
                'discipline_categorie_critere_id' => 104,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            357 =>
            array(
                'id' => 390,
                'discipline_categorie_critere_id' => 104,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            358 =>
            array(
                'id' => 391,
                'discipline_categorie_critere_id' => 105,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            359 =>
            array(
                'id' => 392,
                'discipline_categorie_critere_id' => 105,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            360 =>
            array(
                'id' => 393,
                'discipline_categorie_critere_id' => 105,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            361 =>
            array(
                'id' => 394,
                'discipline_categorie_critere_id' => 106,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            362 =>
            array(
                'id' => 395,
                'discipline_categorie_critere_id' => 106,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            363 =>
            array(
                'id' => 396,
                'discipline_categorie_critere_id' => 106,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            364 =>
            array(
                'id' => 397,
                'discipline_categorie_critere_id' => 106,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            365 =>
            array(
                'id' => 398,
                'discipline_categorie_critere_id' => 107,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            366 =>
            array(
                'id' => 399,
                'discipline_categorie_critere_id' => 107,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            367 =>
            array(
                'id' => 400,
                'discipline_categorie_critere_id' => 107,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            368 =>
            array(
                'id' => 401,
                'discipline_categorie_critere_id' => 107,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            369 =>
            array(
                'id' => 402,
                'discipline_categorie_critere_id' => 107,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            370 =>
            array(
                'id' => 403,
                'discipline_categorie_critere_id' => 108,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            371 =>
            array(
                'id' => 404,
                'discipline_categorie_critere_id' => 108,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            372 =>
            array(
                'id' => 405,
                'discipline_categorie_critere_id' => 108,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            373 =>
            array(
                'id' => 406,
                'discipline_categorie_critere_id' => 108,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            374 =>
            array(
                'id' => 407,
                'discipline_categorie_critere_id' => 109,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            375 =>
            array(
                'id' => 408,
                'discipline_categorie_critere_id' => 109,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            376 =>
            array(
                'id' => 409,
                'discipline_categorie_critere_id' => 109,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            377 =>
            array(
                'id' => 410,
                'discipline_categorie_critere_id' => 109,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            378 =>
            array(
                'id' => 411,
                'discipline_categorie_critere_id' => 109,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            379 =>
            array(
                'id' => 412,
                'discipline_categorie_critere_id' => 110,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            380 =>
            array(
                'id' => 413,
                'discipline_categorie_critere_id' => 110,
                'valeur' => 'E-learning',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            381 =>
            array(
                'id' => 414,
                'discipline_categorie_critere_id' => 110,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            382 =>
            array(
                'id' => 514,
                'discipline_categorie_critere_id' => 138,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            383 =>
            array(
                'id' => 515,
                'discipline_categorie_critere_id' => 138,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            384 =>
            array(
                'id' => 516,
                'discipline_categorie_critere_id' => 138,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            385 =>
            array(
                'id' => 517,
                'discipline_categorie_critere_id' => 138,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            386 =>
            array(
                'id' => 518,
                'discipline_categorie_critere_id' => 139,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            387 =>
            array(
                'id' => 519,
                'discipline_categorie_critere_id' => 139,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            388 =>
            array(
                'id' => 520,
                'discipline_categorie_critere_id' => 139,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            389 =>
            array(
                'id' => 521,
                'discipline_categorie_critere_id' => 139,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            390 =>
            array(
                'id' => 522,
                'discipline_categorie_critere_id' => 139,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            391 =>
            array(
                'id' => 523,
                'discipline_categorie_critere_id' => 140,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            392 =>
            array(
                'id' => 524,
                'discipline_categorie_critere_id' => 140,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            393 =>
            array(
                'id' => 525,
                'discipline_categorie_critere_id' => 140,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            394 =>
            array(
                'id' => 526,
                'discipline_categorie_critere_id' => 141,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            395 =>
            array(
                'id' => 527,
                'discipline_categorie_critere_id' => 141,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            396 =>
            array(
                'id' => 528,
                'discipline_categorie_critere_id' => 141,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            397 =>
            array(
                'id' => 529,
                'discipline_categorie_critere_id' => 141,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            398 =>
            array(
                'id' => 530,
                'discipline_categorie_critere_id' => 142,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            399 =>
            array(
                'id' => 531,
                'discipline_categorie_critere_id' => 142,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            400 =>
            array(
                'id' => 532,
                'discipline_categorie_critere_id' => 142,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            401 =>
            array(
                'id' => 533,
                'discipline_categorie_critere_id' => 142,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            402 =>
            array(
                'id' => 534,
                'discipline_categorie_critere_id' => 142,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            403 =>
            array(
                'id' => 535,
                'discipline_categorie_critere_id' => 143,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            404 =>
            array(
                'id' => 536,
                'discipline_categorie_critere_id' => 143,
                'valeur' => 'E-learning',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            405 =>
            array(
                'id' => 537,
                'discipline_categorie_critere_id' => 143,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            406 =>
            array(
                'id' => 538,
                'discipline_categorie_critere_id' => 144,
                'valeur' => 'Sur événement',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            407 =>
            array(
                'id' => 539,
                'discipline_categorie_critere_id' => 144,
                'valeur' => 'En entreprise',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            408 =>
            array(
                'id' => 540,
                'discipline_categorie_critere_id' => 144,
                'valeur' => 'En établissement scolaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            409 =>
            array(
                'id' => 541,
                'discipline_categorie_critere_id' => 145,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            410 =>
            array(
                'id' => 542,
                'discipline_categorie_critere_id' => 145,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            411 =>
            array(
                'id' => 543,
                'discipline_categorie_critere_id' => 145,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            412 =>
            array(
                'id' => 544,
                'discipline_categorie_critere_id' => 145,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            413 =>
            array(
                'id' => 545,
                'discipline_categorie_critere_id' => 146,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            414 =>
            array(
                'id' => 546,
                'discipline_categorie_critere_id' => 146,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            415 =>
            array(
                'id' => 547,
                'discipline_categorie_critere_id' => 146,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            416 =>
            array(
                'id' => 548,
                'discipline_categorie_critere_id' => 146,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            417 =>
            array(
                'id' => 549,
                'discipline_categorie_critere_id' => 146,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            418 =>
            array(
                'id' => 550,
                'discipline_categorie_critere_id' => 147,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            419 =>
            array(
                'id' => 551,
                'discipline_categorie_critere_id' => 147,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            420 =>
            array(
                'id' => 552,
                'discipline_categorie_critere_id' => 147,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            421 =>
            array(
                'id' => 553,
                'discipline_categorie_critere_id' => 148,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            422 =>
            array(
                'id' => 554,
                'discipline_categorie_critere_id' => 148,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            423 =>
            array(
                'id' => 555,
                'discipline_categorie_critere_id' => 148,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            424 =>
            array(
                'id' => 556,
                'discipline_categorie_critere_id' => 148,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            425 =>
            array(
                'id' => 557,
                'discipline_categorie_critere_id' => 149,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            426 =>
            array(
                'id' => 558,
                'discipline_categorie_critere_id' => 149,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            427 =>
            array(
                'id' => 559,
                'discipline_categorie_critere_id' => 149,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            428 =>
            array(
                'id' => 560,
                'discipline_categorie_critere_id' => 149,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            429 =>
            array(
                'id' => 561,
                'discipline_categorie_critere_id' => 149,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            430 =>
            array(
                'id' => 562,
                'discipline_categorie_critere_id' => 150,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            431 =>
            array(
                'id' => 563,
                'discipline_categorie_critere_id' => 151,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            432 =>
            array(
                'id' => 564,
                'discipline_categorie_critere_id' => 151,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            433 =>
            array(
                'id' => 565,
                'discipline_categorie_critere_id' => 151,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            434 =>
            array(
                'id' => 566,
                'discipline_categorie_critere_id' => 151,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            435 =>
            array(
                'id' => 612,
                'discipline_categorie_critere_id' => 163,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            436 =>
            array(
                'id' => 613,
                'discipline_categorie_critere_id' => 163,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            437 =>
            array(
                'id' => 614,
                'discipline_categorie_critere_id' => 163,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            438 =>
            array(
                'id' => 615,
                'discipline_categorie_critere_id' => 163,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            439 =>
            array(
                'id' => 616,
                'discipline_categorie_critere_id' => 164,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            440 =>
            array(
                'id' => 617,
                'discipline_categorie_critere_id' => 164,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            441 =>
            array(
                'id' => 618,
                'discipline_categorie_critere_id' => 164,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            442 =>
            array(
                'id' => 619,
                'discipline_categorie_critere_id' => 164,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            443 =>
            array(
                'id' => 620,
                'discipline_categorie_critere_id' => 164,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            444 =>
            array(
                'id' => 621,
                'discipline_categorie_critere_id' => 165,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            445 =>
            array(
                'id' => 622,
                'discipline_categorie_critere_id' => 165,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            446 =>
            array(
                'id' => 623,
                'discipline_categorie_critere_id' => 165,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            447 =>
            array(
                'id' => 624,
                'discipline_categorie_critere_id' => 166,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            448 =>
            array(
                'id' => 625,
                'discipline_categorie_critere_id' => 166,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            449 =>
            array(
                'id' => 626,
                'discipline_categorie_critere_id' => 166,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            450 =>
            array(
                'id' => 627,
                'discipline_categorie_critere_id' => 166,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            451 =>
            array(
                'id' => 628,
                'discipline_categorie_critere_id' => 167,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            452 =>
            array(
                'id' => 629,
                'discipline_categorie_critere_id' => 167,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            453 =>
            array(
                'id' => 630,
                'discipline_categorie_critere_id' => 167,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            454 =>
            array(
                'id' => 631,
                'discipline_categorie_critere_id' => 167,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            455 =>
            array(
                'id' => 632,
                'discipline_categorie_critere_id' => 167,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            456 =>
            array(
                'id' => 633,
                'discipline_categorie_critere_id' => 168,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            457 =>
            array(
                'id' => 634,
                'discipline_categorie_critere_id' => 168,
                'valeur' => 'E-learning',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            458 =>
            array(
                'id' => 635,
                'discipline_categorie_critere_id' => 168,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            459 =>
            array(
                'id' => 636,
                'discipline_categorie_critere_id' => 169,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            460 =>
            array(
                'id' => 637,
                'discipline_categorie_critere_id' => 169,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            461 =>
            array(
                'id' => 638,
                'discipline_categorie_critere_id' => 169,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            462 =>
            array(
                'id' => 639,
                'discipline_categorie_critere_id' => 169,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            463 =>
            array(
                'id' => 640,
                'discipline_categorie_critere_id' => 170,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            464 =>
            array(
                'id' => 641,
                'discipline_categorie_critere_id' => 170,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            465 =>
            array(
                'id' => 642,
                'discipline_categorie_critere_id' => 170,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            466 =>
            array(
                'id' => 643,
                'discipline_categorie_critere_id' => 170,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            467 =>
            array(
                'id' => 644,
                'discipline_categorie_critere_id' => 170,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            468 =>
            array(
                'id' => 645,
                'discipline_categorie_critere_id' => 171,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            469 =>
            array(
                'id' => 646,
                'discipline_categorie_critere_id' => 171,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            470 =>
            array(
                'id' => 647,
                'discipline_categorie_critere_id' => 171,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            471 =>
            array(
                'id' => 648,
                'discipline_categorie_critere_id' => 171,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            472 =>
            array(
                'id' => 649,
                'discipline_categorie_critere_id' => 172,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            473 =>
            array(
                'id' => 650,
                'discipline_categorie_critere_id' => 172,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            474 =>
            array(
                'id' => 651,
                'discipline_categorie_critere_id' => 172,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            475 =>
            array(
                'id' => 652,
                'discipline_categorie_critere_id' => 172,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            476 =>
            array(
                'id' => 653,
                'discipline_categorie_critere_id' => 172,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            477 =>
            array(
                'id' => 654,
                'discipline_categorie_critere_id' => 173,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            478 =>
            array(
                'id' => 655,
                'discipline_categorie_critere_id' => 173,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            479 =>
            array(
                'id' => 656,
                'discipline_categorie_critere_id' => 173,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            480 =>
            array(
                'id' => 657,
                'discipline_categorie_critere_id' => 174,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            481 =>
            array(
                'id' => 658,
                'discipline_categorie_critere_id' => 174,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            482 =>
            array(
                'id' => 659,
                'discipline_categorie_critere_id' => 174,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            483 =>
            array(
                'id' => 660,
                'discipline_categorie_critere_id' => 174,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            484 =>
            array(
                'id' => 661,
                'discipline_categorie_critere_id' => 175,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            485 =>
            array(
                'id' => 662,
                'discipline_categorie_critere_id' => 175,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            486 =>
            array(
                'id' => 663,
                'discipline_categorie_critere_id' => 175,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            487 =>
            array(
                'id' => 664,
                'discipline_categorie_critere_id' => 175,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            488 =>
            array(
                'id' => 665,
                'discipline_categorie_critere_id' => 175,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            489 =>
            array(
                'id' => 666,
                'discipline_categorie_critere_id' => 176,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            490 =>
            array(
                'id' => 667,
                'discipline_categorie_critere_id' => 176,
                'valeur' => 'Par internet',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            491 =>
            array(
                'id' => 668,
                'discipline_categorie_critere_id' => 176,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            492 =>
            array(
                'id' => 669,
                'discipline_categorie_critere_id' => 177,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            493 =>
            array(
                'id' => 670,
                'discipline_categorie_critere_id' => 177,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            494 =>
            array(
                'id' => 671,
                'discipline_categorie_critere_id' => 177,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            495 =>
            array(
                'id' => 672,
                'discipline_categorie_critere_id' => 177,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            496 =>
            array(
                'id' => 673,
                'discipline_categorie_critere_id' => 177,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            497 =>
            array(
                'id' => 674,
                'discipline_categorie_critere_id' => 178,
                'valeur' => 'Par internet',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            498 =>
            array(
                'id' => 675,
                'discipline_categorie_critere_id' => 178,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            499 =>
            array(
                'id' => 676,
                'discipline_categorie_critere_id' => 179,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
        ));
        DB::table('liens_disciplines_categories_criteres_valeurs')->insert(array(
            0 =>
            array(
                'id' => 677,
                'discipline_categorie_critere_id' => 179,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            1 =>
            array(
                'id' => 678,
                'discipline_categorie_critere_id' => 179,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            2 =>
            array(
                'id' => 679,
                'discipline_categorie_critere_id' => 179,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            3 =>
            array(
                'id' => 680,
                'discipline_categorie_critere_id' => 179,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            4 =>
            array(
                'id' => 681,
                'discipline_categorie_critere_id' => 180,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            5 =>
            array(
                'id' => 682,
                'discipline_categorie_critere_id' => 180,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            6 =>
            array(
                'id' => 683,
                'discipline_categorie_critere_id' => 180,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            7 =>
            array(
                'id' => 684,
                'discipline_categorie_critere_id' => 181,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            8 =>
            array(
                'id' => 685,
                'discipline_categorie_critere_id' => 181,
                'valeur' => 'Par internet',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            9 =>
            array(
                'id' => 686,
                'discipline_categorie_critere_id' => 181,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            10 =>
            array(
                'id' => 687,
                'discipline_categorie_critere_id' => 182,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            11 =>
            array(
                'id' => 688,
                'discipline_categorie_critere_id' => 182,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            12 =>
            array(
                'id' => 689,
                'discipline_categorie_critere_id' => 182,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            13 =>
            array(
                'id' => 690,
                'discipline_categorie_critere_id' => 183,
                'valeur' => 'En entreprise',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            14 =>
            array(
                'id' => 691,
                'discipline_categorie_critere_id' => 183,
                'valeur' => 'Sur événement',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            15 =>
            array(
                'id' => 692,
                'discipline_categorie_critere_id' => 184,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            16 =>
            array(
                'id' => 693,
                'discipline_categorie_critere_id' => 184,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            17 =>
            array(
                'id' => 694,
                'discipline_categorie_critere_id' => 184,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            18 =>
            array(
                'id' => 695,
                'discipline_categorie_critere_id' => 184,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            19 =>
            array(
                'id' => 696,
                'discipline_categorie_critere_id' => 185,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            20 =>
            array(
                'id' => 697,
                'discipline_categorie_critere_id' => 185,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            21 =>
            array(
                'id' => 698,
                'discipline_categorie_critere_id' => 185,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            22 =>
            array(
                'id' => 699,
                'discipline_categorie_critere_id' => 185,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            23 =>
            array(
                'id' => 700,
                'discipline_categorie_critere_id' => 185,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            24 =>
            array(
                'id' => 701,
                'discipline_categorie_critere_id' => 186,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            25 =>
            array(
                'id' => 702,
                'discipline_categorie_critere_id' => 186,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            26 =>
            array(
                'id' => 703,
                'discipline_categorie_critere_id' => 186,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            27 =>
            array(
                'id' => 704,
                'discipline_categorie_critere_id' => 187,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            28 =>
            array(
                'id' => 705,
                'discipline_categorie_critere_id' => 187,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            29 =>
            array(
                'id' => 706,
                'discipline_categorie_critere_id' => 187,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            30 =>
            array(
                'id' => 707,
                'discipline_categorie_critere_id' => 187,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            31 =>
            array(
                'id' => 708,
                'discipline_categorie_critere_id' => 188,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            32 =>
            array(
                'id' => 709,
                'discipline_categorie_critere_id' => 188,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            33 =>
            array(
                'id' => 710,
                'discipline_categorie_critere_id' => 188,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            34 =>
            array(
                'id' => 711,
                'discipline_categorie_critere_id' => 188,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            35 =>
            array(
                'id' => 712,
                'discipline_categorie_critere_id' => 188,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            36 =>
            array(
                'id' => 713,
                'discipline_categorie_critere_id' => 189,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            37 =>
            array(
                'id' => 714,
                'discipline_categorie_critere_id' => 189,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            38 =>
            array(
                'id' => 715,
                'discipline_categorie_critere_id' => 190,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            39 =>
            array(
                'id' => 716,
                'discipline_categorie_critere_id' => 190,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            40 =>
            array(
                'id' => 717,
                'discipline_categorie_critere_id' => 190,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            41 =>
            array(
                'id' => 718,
                'discipline_categorie_critere_id' => 191,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            42 =>
            array(
                'id' => 719,
                'discipline_categorie_critere_id' => 191,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            43 =>
            array(
                'id' => 720,
                'discipline_categorie_critere_id' => 191,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            44 =>
            array(
                'id' => 721,
                'discipline_categorie_critere_id' => 191,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            45 =>
            array(
                'id' => 722,
                'discipline_categorie_critere_id' => 192,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            46 =>
            array(
                'id' => 723,
                'discipline_categorie_critere_id' => 192,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            47 =>
            array(
                'id' => 724,
                'discipline_categorie_critere_id' => 193,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            48 =>
            array(
                'id' => 725,
                'discipline_categorie_critere_id' => 193,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            49 =>
            array(
                'id' => 726,
                'discipline_categorie_critere_id' => 193,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            50 =>
            array(
                'id' => 727,
                'discipline_categorie_critere_id' => 193,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            51 =>
            array(
                'id' => 728,
                'discipline_categorie_critere_id' => 194,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            52 =>
            array(
                'id' => 729,
                'discipline_categorie_critere_id' => 194,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            53 =>
            array(
                'id' => 730,
                'discipline_categorie_critere_id' => 194,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            54 =>
            array(
                'id' => 731,
                'discipline_categorie_critere_id' => 194,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            55 =>
            array(
                'id' => 732,
                'discipline_categorie_critere_id' => 194,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            56 =>
            array(
                'id' => 733,
                'discipline_categorie_critere_id' => 195,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            57 =>
            array(
                'id' => 734,
                'discipline_categorie_critere_id' => 195,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            58 =>
            array(
                'id' => 735,
                'discipline_categorie_critere_id' => 195,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            59 =>
            array(
                'id' => 736,
                'discipline_categorie_critere_id' => 196,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            60 =>
            array(
                'id' => 737,
                'discipline_categorie_critere_id' => 196,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            61 =>
            array(
                'id' => 738,
                'discipline_categorie_critere_id' => 196,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            62 =>
            array(
                'id' => 739,
                'discipline_categorie_critere_id' => 196,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            63 =>
            array(
                'id' => 740,
                'discipline_categorie_critere_id' => 197,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            64 =>
            array(
                'id' => 741,
                'discipline_categorie_critere_id' => 197,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            65 =>
            array(
                'id' => 742,
                'discipline_categorie_critere_id' => 197,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            66 =>
            array(
                'id' => 743,
                'discipline_categorie_critere_id' => 197,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            67 =>
            array(
                'id' => 744,
                'discipline_categorie_critere_id' => 197,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            68 =>
            array(
                'id' => 745,
                'discipline_categorie_critere_id' => 198,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            69 =>
            array(
                'id' => 746,
                'discipline_categorie_critere_id' => 198,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            70 =>
            array(
                'id' => 747,
                'discipline_categorie_critere_id' => 198,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            71 =>
            array(
                'id' => 748,
                'discipline_categorie_critere_id' => 198,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            72 =>
            array(
                'id' => 749,
                'discipline_categorie_critere_id' => 199,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            73 =>
            array(
                'id' => 750,
                'discipline_categorie_critere_id' => 199,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            74 =>
            array(
                'id' => 751,
                'discipline_categorie_critere_id' => 199,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            75 =>
            array(
                'id' => 752,
                'discipline_categorie_critere_id' => 199,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            76 =>
            array(
                'id' => 753,
                'discipline_categorie_critere_id' => 200,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            77 =>
            array(
                'id' => 754,
                'discipline_categorie_critere_id' => 200,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            78 =>
            array(
                'id' => 755,
                'discipline_categorie_critere_id' => 200,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            79 =>
            array(
                'id' => 756,
                'discipline_categorie_critere_id' => 200,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            80 =>
            array(
                'id' => 757,
                'discipline_categorie_critere_id' => 200,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            81 =>
            array(
                'id' => 758,
                'discipline_categorie_critere_id' => 201,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            82 =>
            array(
                'id' => 759,
                'discipline_categorie_critere_id' => 201,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            83 =>
            array(
                'id' => 760,
                'discipline_categorie_critere_id' => 201,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            84 =>
            array(
                'id' => 761,
                'discipline_categorie_critere_id' => 201,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            85 =>
            array(
                'id' => 762,
                'discipline_categorie_critere_id' => 202,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            86 =>
            array(
                'id' => 763,
                'discipline_categorie_critere_id' => 202,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            87 =>
            array(
                'id' => 764,
                'discipline_categorie_critere_id' => 202,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            88 =>
            array(
                'id' => 765,
                'discipline_categorie_critere_id' => 202,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            89 =>
            array(
                'id' => 766,
                'discipline_categorie_critere_id' => 202,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            90 =>
            array(
                'id' => 767,
                'discipline_categorie_critere_id' => 203,
                'valeur' => 'Dans un bowling',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            91 =>
            array(
                'id' => 768,
                'discipline_categorie_critere_id' => 204,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            92 =>
            array(
                'id' => 769,
                'discipline_categorie_critere_id' => 204,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            93 =>
            array(
                'id' => 770,
                'discipline_categorie_critere_id' => 204,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            94 =>
            array(
                'id' => 771,
                'discipline_categorie_critere_id' => 205,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            95 =>
            array(
                'id' => 772,
                'discipline_categorie_critere_id' => 205,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            96 =>
            array(
                'id' => 773,
                'discipline_categorie_critere_id' => 205,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            97 =>
            array(
                'id' => 774,
                'discipline_categorie_critere_id' => 205,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            98 =>
            array(
                'id' => 775,
                'discipline_categorie_critere_id' => 206,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            99 =>
            array(
                'id' => 776,
                'discipline_categorie_critere_id' => 206,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            100 =>
            array(
                'id' => 777,
                'discipline_categorie_critere_id' => 206,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            101 =>
            array(
                'id' => 778,
                'discipline_categorie_critere_id' => 206,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            102 =>
            array(
                'id' => 779,
                'discipline_categorie_critere_id' => 207,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            103 =>
            array(
                'id' => 780,
                'discipline_categorie_critere_id' => 207,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            104 =>
            array(
                'id' => 781,
                'discipline_categorie_critere_id' => 207,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            105 =>
            array(
                'id' => 782,
                'discipline_categorie_critere_id' => 207,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            106 =>
            array(
                'id' => 783,
                'discipline_categorie_critere_id' => 207,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            107 =>
            array(
                'id' => 784,
                'discipline_categorie_critere_id' => 208,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            108 =>
            array(
                'id' => 785,
                'discipline_categorie_critere_id' => 208,
                'valeur' => 'A la carte',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            109 =>
            array(
                'id' => 786,
                'discipline_categorie_critere_id' => 208,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            110 =>
            array(
                'id' => 787,
                'discipline_categorie_critere_id' => 209,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            111 =>
            array(
                'id' => 788,
                'discipline_categorie_critere_id' => 209,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            112 =>
            array(
                'id' => 789,
                'discipline_categorie_critere_id' => 209,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            113 =>
            array(
                'id' => 790,
                'discipline_categorie_critere_id' => 209,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            114 =>
            array(
                'id' => 791,
                'discipline_categorie_critere_id' => 210,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            115 =>
            array(
                'id' => 792,
                'discipline_categorie_critere_id' => 210,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            116 =>
            array(
                'id' => 793,
                'discipline_categorie_critere_id' => 210,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            117 =>
            array(
                'id' => 794,
                'discipline_categorie_critere_id' => 210,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            118 =>
            array(
                'id' => 795,
                'discipline_categorie_critere_id' => 210,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            119 =>
            array(
                'id' => 796,
                'discipline_categorie_critere_id' => 211,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            120 =>
            array(
                'id' => 797,
                'discipline_categorie_critere_id' => 211,
                'valeur' => 'Par internet',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            121 =>
            array(
                'id' => 798,
                'discipline_categorie_critere_id' => 211,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            122 =>
            array(
                'id' => 799,
                'discipline_categorie_critere_id' => 212,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            123 =>
            array(
                'id' => 800,
                'discipline_categorie_critere_id' => 212,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            124 =>
            array(
                'id' => 801,
                'discipline_categorie_critere_id' => 212,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            125 =>
            array(
                'id' => 802,
                'discipline_categorie_critere_id' => 212,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            126 =>
            array(
                'id' => 803,
                'discipline_categorie_critere_id' => 213,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            127 =>
            array(
                'id' => 804,
                'discipline_categorie_critere_id' => 213,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            128 =>
            array(
                'id' => 805,
                'discipline_categorie_critere_id' => 213,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            129 =>
            array(
                'id' => 806,
                'discipline_categorie_critere_id' => 213,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            130 =>
            array(
                'id' => 807,
                'discipline_categorie_critere_id' => 213,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            131 =>
            array(
                'id' => 808,
                'discipline_categorie_critere_id' => 214,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            132 =>
            array(
                'id' => 809,
                'discipline_categorie_critere_id' => 214,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            133 =>
            array(
                'id' => 810,
                'discipline_categorie_critere_id' => 214,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            134 =>
            array(
                'id' => 811,
                'discipline_categorie_critere_id' => 214,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            135 =>
            array(
                'id' => 812,
                'discipline_categorie_critere_id' => 215,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            136 =>
            array(
                'id' => 813,
                'discipline_categorie_critere_id' => 215,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            137 =>
            array(
                'id' => 814,
                'discipline_categorie_critere_id' => 215,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            138 =>
            array(
                'id' => 815,
                'discipline_categorie_critere_id' => 215,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            139 =>
            array(
                'id' => 816,
                'discipline_categorie_critere_id' => 215,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            140 =>
            array(
                'id' => 817,
                'discipline_categorie_critere_id' => 216,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            141 =>
            array(
                'id' => 818,
                'discipline_categorie_critere_id' => 216,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            142 =>
            array(
                'id' => 819,
                'discipline_categorie_critere_id' => 216,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            143 =>
            array(
                'id' => 820,
                'discipline_categorie_critere_id' => 216,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            144 =>
            array(
                'id' => 821,
                'discipline_categorie_critere_id' => 217,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            145 =>
            array(
                'id' => 822,
                'discipline_categorie_critere_id' => 217,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            146 =>
            array(
                'id' => 823,
                'discipline_categorie_critere_id' => 217,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            147 =>
            array(
                'id' => 824,
                'discipline_categorie_critere_id' => 217,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            148 =>
            array(
                'id' => 825,
                'discipline_categorie_critere_id' => 217,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            149 =>
            array(
                'id' => 826,
                'discipline_categorie_critere_id' => 218,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            150 =>
            array(
                'id' => 827,
                'discipline_categorie_critere_id' => 219,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            151 =>
            array(
                'id' => 828,
                'discipline_categorie_critere_id' => 219,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            152 =>
            array(
                'id' => 829,
                'discipline_categorie_critere_id' => 219,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            153 =>
            array(
                'id' => 830,
                'discipline_categorie_critere_id' => 219,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            154 =>
            array(
                'id' => 831,
                'discipline_categorie_critere_id' => 220,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            155 =>
            array(
                'id' => 832,
                'discipline_categorie_critere_id' => 220,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            156 =>
            array(
                'id' => 833,
                'discipline_categorie_critere_id' => 220,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            157 =>
            array(
                'id' => 834,
                'discipline_categorie_critere_id' => 220,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            158 =>
            array(
                'id' => 835,
                'discipline_categorie_critere_id' => 221,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            159 =>
            array(
                'id' => 836,
                'discipline_categorie_critere_id' => 221,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            160 =>
            array(
                'id' => 837,
                'discipline_categorie_critere_id' => 221,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            161 =>
            array(
                'id' => 838,
                'discipline_categorie_critere_id' => 221,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            162 =>
            array(
                'id' => 839,
                'discipline_categorie_critere_id' => 221,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            163 =>
            array(
                'id' => 840,
                'discipline_categorie_critere_id' => 222,
                'valeur' => 'Intitiation',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            164 =>
            array(
                'id' => 841,
                'discipline_categorie_critere_id' => 222,
                'valeur' => 'A lannée',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            165 =>
            array(
                'id' => 842,
                'discipline_categorie_critere_id' => 223,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            166 =>
            array(
                'id' => 843,
                'discipline_categorie_critere_id' => 223,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            167 =>
            array(
                'id' => 844,
                'discipline_categorie_critere_id' => 223,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            168 =>
            array(
                'id' => 845,
                'discipline_categorie_critere_id' => 223,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            169 =>
            array(
                'id' => 846,
                'discipline_categorie_critere_id' => 224,
                'valeur' => 'Tout niveau',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            170 =>
            array(
                'id' => 847,
                'discipline_categorie_critere_id' => 224,
                'valeur' => 'Débutants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            171 =>
            array(
                'id' => 848,
                'discipline_categorie_critere_id' => 224,
                'valeur' => 'Niveau intermédiaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            172 =>
            array(
                'id' => 849,
                'discipline_categorie_critere_id' => 224,
                'valeur' => 'Bon niveau',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            173 =>
            array(
                'id' => 850,
                'discipline_categorie_critere_id' => 224,
                'valeur' => 'Niveau expert',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            174 =>
            array(
                'id' => 851,
                'discipline_categorie_critere_id' => 225,
                'valeur' => 'A domicile',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            175 =>
            array(
                'id' => 852,
                'discipline_categorie_critere_id' => 225,
                'valeur' => 'Par internet',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            176 =>
            array(
                'id' => 853,
                'discipline_categorie_critere_id' => 225,
                'valeur' => 'Dans un lieu',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            177 =>
            array(
                'id' => 854,
                'discipline_categorie_critere_id' => 226,
                'valeur' => 'En entreprise',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            178 =>
            array(
                'id' => 855,
                'discipline_categorie_critere_id' => 226,
                'valeur' => 'En établissement scolaire',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            179 =>
            array(
                'id' => 856,
                'discipline_categorie_critere_id' => 226,
                'valeur' => 'Sur événement',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            180 =>
            array(
                'id' => 857,
                'discipline_categorie_critere_id' => 227,
                'valeur' => 'Enfants',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            181 =>
            array(
                'id' => 858,
                'discipline_categorie_critere_id' => 227,
                'valeur' => 'Ados',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            182 =>
            array(
                'id' => 859,
                'discipline_categorie_critere_id' => 227,
                'valeur' => 'Adultes',
                'defaut' => 0,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            183 =>
            array(
                'id' => 860,
                'discipline_categorie_critere_id' => 227,
                'valeur' => 'Tout age',
                'defaut' => 1,
                'created_at' => '2023-06-22 20:07:31',
                'updated_at' => '2023-06-22 20:07:31',
            ),
            184 =>
            array(
                'id' => 862,
                'discipline_categorie_critere_id' => 93,
                'valeur' => 'Tout niveau',
                'defaut' => 0,
                'created_at' => '2023-08-21 18:34:34',
                'updated_at' => '2023-08-21 18:34:34',
            ),
        ));


    }
}
