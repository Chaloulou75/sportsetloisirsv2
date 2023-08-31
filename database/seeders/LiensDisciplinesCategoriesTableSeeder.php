<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LiensDisciplinesCategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('liens_disciplines_categories')->delete();

        DB::table('liens_disciplines_categories')->insert(array(
            0 =>
            array(
                'id' => 213,
                'discipline_id' => 152,
                'categorie_id' => 34,
                'nom_categorie_pro' => 'Parcours',
                'nom_categorie_client' => 'Parcours',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            1 =>
            array(
                'id' => 214,
                'discipline_id' => 122,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            2 =>
            array(
                'id' => 215,
                'discipline_id' => 122,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages',
                'nom_categorie_client' => 'Stages',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            3 =>
            array(
                'id' => 216,
                'discipline_id' => 122,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            4 =>
            array(
                'id' => 217,
                'discipline_id' => 246,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Activités en club',
                'nom_categorie_client' => 'Activités en club',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            5 =>
            array(
                'id' => 218,
                'discipline_id' => 246,
                'categorie_id' => 36,
                'nom_categorie_pro' => 'Location de modèles',
                'nom_categorie_client' => 'Location de modèles',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            6 =>
            array(
                'id' => 219,
                'discipline_id' => 246,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Compétitions',
                'nom_categorie_client' => 'Compétitions',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            7 =>
            array(
                'id' => 220,
                'discipline_id' => 246,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Acheter un modèle',
                'nom_categorie_client' => 'Acheter un modèle',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            8 =>
            array(
                'id' => 221,
                'discipline_id' => 195,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            9 =>
            array(
                'id' => 222,
                'discipline_id' => 195,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages',
                'nom_categorie_client' => 'Stages',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            10 =>
            array(
                'id' => 223,
                'discipline_id' => 195,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            11 =>
            array(
                'id' => 224,
                'discipline_id' => 195,
                'categorie_id' => 37,
                'nom_categorie_pro' => 'Prestations',
                'nom_categorie_client' => 'Prestations',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            12 =>
            array(
                'id' => 225,
                'discipline_id' => 195,
                'categorie_id' => 38,
                'nom_categorie_pro' => 'Concerts',
                'nom_categorie_client' => 'Concerts',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            13 =>
            array(
                'id' => 226,
                'discipline_id' => 195,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Acheter un accordéon',
                'nom_categorie_client' => 'Acheter un accordéon',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            14 =>
            array(
                'id' => 227,
                'discipline_id' => 32,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            15 =>
            array(
                'id' => 228,
                'discipline_id' => 32,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages',
                'nom_categorie_client' => 'Stages',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            16 =>
            array(
                'id' => 229,
                'discipline_id' => 32,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            17 =>
            array(
                'id' => 230,
                'discipline_id' => 32,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Compétitions',
                'nom_categorie_client' => 'Compétitions',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            18 =>
            array(
                'id' => 231,
                'discipline_id' => 32,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vendre des articles',
                'nom_categorie_client' => 'Vendre des articles',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            19 =>
            array(
                'id' => 232,
                'discipline_id' => 196,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            20 =>
            array(
                'id' => 233,
                'discipline_id' => 196,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages',
                'nom_categorie_client' => 'Stages',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            21 =>
            array(
                'id' => 234,
                'discipline_id' => 196,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            22 =>
            array(
                'id' => 235,
                'discipline_id' => 196,
                'categorie_id' => 37,
                'nom_categorie_pro' => 'Prestations',
                'nom_categorie_client' => 'Prestations',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            23 =>
            array(
                'id' => 236,
                'discipline_id' => 196,
                'categorie_id' => 38,
                'nom_categorie_pro' => 'Concerts',
                'nom_categorie_client' => 'Concerts',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            24 =>
            array(
                'id' => 237,
                'discipline_id' => 196,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Concours',
                'nom_categorie_client' => 'Concours',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            25 =>
            array(
                'id' => 238,
                'discipline_id' => 196,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vendre des articles',
                'nom_categorie_client' => 'Vendre des articles',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            26 =>
            array(
                'id' => 239,
                'discipline_id' => 103,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            27 =>
            array(
                'id' => 240,
                'discipline_id' => 103,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages',
                'nom_categorie_client' => 'Stages',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            28 =>
            array(
                'id' => 241,
                'discipline_id' => 103,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            29 =>
            array(
                'id' => 242,
                'discipline_id' => 103,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Compétitions',
                'nom_categorie_client' => 'Compétitions',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            30 =>
            array(
                'id' => 243,
                'discipline_id' => 103,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles apnée',
                'nom_categorie_client' => 'Vente articles apnée',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            31 =>
            array(
                'id' => 244,
                'discipline_id' => 281,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            32 =>
            array(
                'id' => 245,
                'discipline_id' => 281,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            33 =>
            array(
                'id' => 246,
                'discipline_id' => 281,
                'categorie_id' => 34,
                'nom_categorie_pro' => 'Pratique libre',
                'nom_categorie_client' => 'Pratique libre',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            34 =>
            array(
                'id' => 247,
                'discipline_id' => 281,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles aquabike',
                'nom_categorie_client' => 'Vente articles aquabike',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            35 =>
            array(
                'id' => 251,
                'discipline_id' => 123,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            36 =>
            array(
                'id' => 252,
                'discipline_id' => 123,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            37 =>
            array(
                'id' => 253,
                'discipline_id' => 123,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles aquagym',
                'nom_categorie_client' => 'Vente articles aquagym',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            38 =>
            array(
                'id' => 254,
                'discipline_id' => 317,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            39 =>
            array(
                'id' => 255,
                'discipline_id' => 317,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages',
                'nom_categorie_client' => 'Stages',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            40 =>
            array(
                'id' => 256,
                'discipline_id' => 317,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            41 =>
            array(
                'id' => 257,
                'discipline_id' => 317,
                'categorie_id' => 37,
                'nom_categorie_pro' => 'Prestations',
                'nom_categorie_client' => 'Prestations',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            42 =>
            array(
                'id' => 258,
                'discipline_id' => 317,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Concours',
                'nom_categorie_client' => 'Concours',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            43 =>
            array(
                'id' => 259,
                'discipline_id' => 317,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles art floral',
                'nom_categorie_client' => 'Vente articles art floral',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            44 =>
            array(
                'id' => 260,
                'discipline_id' => 125,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Pratique en club',
                'nom_categorie_client' => 'Pratique en club',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            45 =>
            array(
                'id' => 261,
                'discipline_id' => 125,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages',
                'nom_categorie_client' => 'Stages',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            46 =>
            array(
                'id' => 262,
                'discipline_id' => 125,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particliers',
                'nom_categorie_client' => 'Cours particliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            47 =>
            array(
                'id' => 263,
                'discipline_id' => 125,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Compétitions',
                'nom_categorie_client' => 'Compétitions',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            48 =>
            array(
                'id' => 264,
                'discipline_id' => 125,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles athlétisme',
                'nom_categorie_client' => 'Vente articles athlétisme',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            49 =>
            array(
                'id' => 265,
                'discipline_id' => 138,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            50 =>
            array(
                'id' => 266,
                'discipline_id' => 138,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages',
                'nom_categorie_client' => 'Stages',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            51 =>
            array(
                'id' => 267,
                'discipline_id' => 138,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particluliers',
                'nom_categorie_client' => 'Cours particluliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            52 =>
            array(
                'id' => 268,
                'discipline_id' => 138,
                'categorie_id' => 36,
                'nom_categorie_pro' => 'Location d\'avions',
                'nom_categorie_client' => 'Location d\'avions',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            53 =>
            array(
                'id' => 269,
                'discipline_id' => 138,
                'categorie_id' => 37,
                'nom_categorie_pro' => 'Prestations',
                'nom_categorie_client' => 'Prestations',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            54 =>
            array(
                'id' => 270,
                'discipline_id' => 138,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles aviation',
                'nom_categorie_client' => 'Vente articles aviation',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            55 =>
            array(
                'id' => 271,
                'discipline_id' => 102,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Pratique en club',
                'nom_categorie_client' => 'Pratique en club',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            56 =>
            array(
                'id' => 272,
                'discipline_id' => 102,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages et séjours',
                'nom_categorie_client' => 'Stages et séjours',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            57 =>
            array(
                'id' => 273,
                'discipline_id' => 102,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            58 =>
            array(
                'id' => 274,
                'discipline_id' => 102,
                'categorie_id' => 36,
                'nom_categorie_pro' => 'Location',
                'nom_categorie_client' => 'Location',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            59 =>
            array(
                'id' => 275,
                'discipline_id' => 102,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Compétition',
                'nom_categorie_client' => 'Compétition',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            60 =>
            array(
                'id' => 276,
                'discipline_id' => 102,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles aviron',
                'nom_categorie_client' => 'Vente articles aviron',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            61 =>
            array(
                'id' => 277,
                'discipline_id' => 250,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Pratique en club',
                'nom_categorie_client' => 'Pratique en club',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            62 =>
            array(
                'id' => 278,
                'discipline_id' => 250,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            63 =>
            array(
                'id' => 279,
                'discipline_id' => 250,
                'categorie_id' => 36,
                'nom_categorie_pro' => 'Location',
                'nom_categorie_client' => 'Location',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            64 =>
            array(
                'id' => 280,
                'discipline_id' => 250,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Tournois',
                'nom_categorie_client' => 'Tournois',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            65 =>
            array(
                'id' => 281,
                'discipline_id' => 250,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vendre articles babyfoot',
                'nom_categorie_client' => 'Vendre articles babyfoot',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            66 =>
            array(
                'id' => 282,
                'discipline_id' => 1,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Pratique en club',
                'nom_categorie_client' => 'Pratique en club',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            67 =>
            array(
                'id' => 283,
                'discipline_id' => 1,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages et séjours',
                'nom_categorie_client' => 'Stages et séjours',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            68 =>
            array(
                'id' => 284,
                'discipline_id' => 1,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            69 =>
            array(
                'id' => 285,
                'discipline_id' => 1,
                'categorie_id' => 34,
                'nom_categorie_pro' => 'Pratique libre',
                'nom_categorie_client' => 'Pratique libre',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            70 =>
            array(
                'id' => 286,
                'discipline_id' => 1,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Tournois',
                'nom_categorie_client' => 'Tournois',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            71 =>
            array(
                'id' => 287,
                'discipline_id' => 1,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles badminton',
                'nom_categorie_client' => 'Vente articles badminton',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            72 =>
            array(
                'id' => 288,
                'discipline_id' => 269,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Pratique en club',
                'nom_categorie_client' => 'Pratique en club',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            73 =>
            array(
                'id' => 289,
                'discipline_id' => 269,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages',
                'nom_categorie_client' => 'Stages',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            74 =>
            array(
                'id' => 290,
                'discipline_id' => 269,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            75 =>
            array(
                'id' => 291,
                'discipline_id' => 269,
                'categorie_id' => 34,
                'nom_categorie_pro' => 'Pratique libre sur stand',
                'nom_categorie_client' => 'Pratique libre sur stand',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            76 =>
            array(
                'id' => 292,
                'discipline_id' => 269,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'CompCompétitionétition',
                'nom_categorie_client' => 'CompCompétitionétition',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            77 =>
            array(
                'id' => 293,
                'discipline_id' => 269,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles ball trap',
                'nom_categorie_client' => 'Vente articles ball trap',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            78 =>
            array(
                'id' => 294,
                'discipline_id' => 360,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            79 =>
            array(
                'id' => 295,
                'discipline_id' => 360,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages',
                'nom_categorie_client' => 'Stages',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            80 =>
            array(
                'id' => 296,
                'discipline_id' => 360,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            81 =>
            array(
                'id' => 297,
                'discipline_id' => 360,
                'categorie_id' => 37,
                'nom_categorie_pro' => 'Prestations',
                'nom_categorie_client' => 'Prestations',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            82 =>
            array(
                'id' => 298,
                'discipline_id' => 360,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles BD',
                'nom_categorie_client' => 'Vente articles BD',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            83 =>
            array(
                'id' => 299,
                'discipline_id' => 197,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            84 =>
            array(
                'id' => 300,
                'discipline_id' => 197,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            85 =>
            array(
                'id' => 301,
                'discipline_id' => 197,
                'categorie_id' => 37,
                'nom_categorie_pro' => 'Prestations',
                'nom_categorie_client' => 'Prestations',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            86 =>
            array(
                'id' => 302,
                'discipline_id' => 197,
                'categorie_id' => 38,
                'nom_categorie_pro' => 'Concerts',
                'nom_categorie_client' => 'Concerts',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            87 =>
            array(
                'id' => 303,
                'discipline_id' => 197,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles Bandjo',
                'nom_categorie_client' => 'Vente articles Bandjo',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            88 =>
            array(
                'id' => 304,
                'discipline_id' => 2,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Pratique en club',
                'nom_categorie_client' => 'Pratique en club',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            89 =>
            array(
                'id' => 305,
                'discipline_id' => 2,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages et séjours',
                'nom_categorie_client' => 'Stages et séjours',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            90 =>
            array(
                'id' => 306,
                'discipline_id' => 2,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            91 =>
            array(
                'id' => 307,
                'discipline_id' => 2,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Tournois',
                'nom_categorie_client' => 'Tournois',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            92 =>
            array(
                'id' => 308,
                'discipline_id' => 2,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Achat articles Baseball',
                'nom_categorie_client' => 'Achat articles Baseball',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            93 =>
            array(
                'id' => 309,
                'discipline_id' => 3,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Pratique en club',
                'nom_categorie_client' => 'Pratique en club',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            94 =>
            array(
                'id' => 310,
                'discipline_id' => 3,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages et séjours',
                'nom_categorie_client' => 'Stages et séjours',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            95 =>
            array(
                'id' => 311,
                'discipline_id' => 3,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            96 =>
            array(
                'id' => 312,
                'discipline_id' => 3,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Tournois',
                'nom_categorie_client' => 'Tournois',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            97 =>
            array(
                'id' => 313,
                'discipline_id' => 3,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles Basketball',
                'nom_categorie_client' => 'Vente articles Basketball',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            98 =>
            array(
                'id' => 314,
                'discipline_id' => 198,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            99 =>
            array(
                'id' => 315,
                'discipline_id' => 198,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages',
                'nom_categorie_client' => 'Stages',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            100 =>
            array(
                'id' => 316,
                'discipline_id' => 198,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            101 =>
            array(
                'id' => 317,
                'discipline_id' => 198,
                'categorie_id' => 37,
                'nom_categorie_pro' => 'Prestations',
                'nom_categorie_client' => 'Prestations',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            102 =>
            array(
                'id' => 318,
                'discipline_id' => 198,
                'categorie_id' => 38,
                'nom_categorie_pro' => 'Concerts',
                'nom_categorie_client' => 'Concerts',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            103 =>
            array(
                'id' => 319,
                'discipline_id' => 198,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles basse',
                'nom_categorie_client' => 'Vente articles basse',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            104 =>
            array(
                'id' => 320,
                'discipline_id' => 350,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectif navigation',
                'nom_categorie_client' => 'Cours collectif navigation',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            105 =>
            array(
                'id' => 321,
                'discipline_id' => 350,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages permis bateau',
                'nom_categorie_client' => 'Stages permis bateau',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            106 =>
            array(
                'id' => 322,
                'discipline_id' => 350,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            107 =>
            array(
                'id' => 323,
                'discipline_id' => 350,
                'categorie_id' => 36,
                'nom_categorie_pro' => 'Location bateau',
                'nom_categorie_client' => 'Location bateau',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            108 =>
            array(
                'id' => 324,
                'discipline_id' => 350,
                'categorie_id' => 37,
                'nom_categorie_pro' => 'Croisières',
                'nom_categorie_client' => 'Croisières',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            109 =>
            array(
                'id' => 325,
                'discipline_id' => 350,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles bateau',
                'nom_categorie_client' => 'Vente articles bateau',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            110 =>
            array(
                'id' => 326,
                'discipline_id' => 350,
                'categorie_id' => 37,
                'nom_categorie_pro' => 'Promenades en bateau',
                'nom_categorie_client' => 'Promenades en bateau',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            111 =>
            array(
                'id' => 327,
                'discipline_id' => 4,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Pratique en club',
                'nom_categorie_client' => 'Pratique en club',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            112 =>
            array(
                'id' => 328,
                'discipline_id' => 4,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages et séjours',
                'nom_categorie_client' => 'Stages et séjours',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            113 =>
            array(
                'id' => 329,
                'discipline_id' => 4,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Tournois',
                'nom_categorie_client' => 'Tournois',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            114 =>
            array(
                'id' => 330,
                'discipline_id' => 4,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vendre articles Beach soccer',
                'nom_categorie_client' => 'Vendre articles Beach soccer',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            115 =>
            array(
                'id' => 331,
                'discipline_id' => 249,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            116 =>
            array(
                'id' => 332,
                'discipline_id' => 249,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages',
                'nom_categorie_client' => 'Stages',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            117 =>
            array(
                'id' => 333,
                'discipline_id' => 249,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            118 =>
            array(
                'id' => 334,
                'discipline_id' => 249,
                'categorie_id' => 34,
                'nom_categorie_pro' => 'Jeux libre en salle',
                'nom_categorie_client' => 'Jeux libre en salle',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            119 =>
            array(
                'id' => 335,
                'discipline_id' => 249,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Tournois',
                'nom_categorie_client' => 'Tournois',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            120 =>
            array(
                'id' => 336,
                'discipline_id' => 249,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Achat articles billard',
                'nom_categorie_client' => 'Achat articles billard',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            121 =>
            array(
                'id' => 337,
                'discipline_id' => 344,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Session bouée tractée',
                'nom_categorie_client' => 'Session bouée tractée',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            122 =>
            array(
                'id' => 338,
                'discipline_id' => 251,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Jouer en club',
                'nom_categorie_client' => 'Jouer en club',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            123 =>
            array(
                'id' => 339,
                'discipline_id' => 251,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages',
                'nom_categorie_client' => 'Stages',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            124 =>
            array(
                'id' => 340,
                'discipline_id' => 251,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            125 =>
            array(
                'id' => 341,
                'discipline_id' => 251,
                'categorie_id' => 34,
                'nom_categorie_pro' => 'Réserver une partie',
                'nom_categorie_client' => 'Réserver une partie',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            126 =>
            array(
                'id' => 342,
                'discipline_id' => 251,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Tournois',
                'nom_categorie_client' => 'Tournois',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            127 =>
            array(
                'id' => 343,
                'discipline_id' => 251,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles Bowling',
                'nom_categorie_client' => 'Vente articles Bowling',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            128 =>
            array(
                'id' => 344,
                'discipline_id' => 34,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            129 =>
            array(
                'id' => 345,
                'discipline_id' => 34,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages',
                'nom_categorie_client' => 'Stages',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            130 =>
            array(
                'id' => 346,
                'discipline_id' => 34,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            131 =>
            array(
                'id' => 347,
                'discipline_id' => 34,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Tournois',
                'nom_categorie_client' => 'Tournois',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            132 =>
            array(
                'id' => 348,
                'discipline_id' => 34,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente article Boxe',
                'nom_categorie_client' => 'Vente article Boxe',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            133 =>
            array(
                'id' => 349,
                'discipline_id' => 106,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            134 =>
            array(
                'id' => 350,
                'discipline_id' => 106,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages et séjours',
                'nom_categorie_client' => 'Stages et séjours',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            135 =>
            array(
                'id' => 351,
                'discipline_id' => 106,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            136 =>
            array(
                'id' => 352,
                'discipline_id' => 106,
                'categorie_id' => 36,
                'nom_categorie_pro' => 'Location',
                'nom_categorie_client' => 'Location',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            137 =>
            array(
                'id' => 353,
                'discipline_id' => 106,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Courses',
                'nom_categorie_client' => 'Courses',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            138 =>
            array(
                'id' => 354,
                'discipline_id' => 106,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles Char à voile',
                'nom_categorie_client' => 'Vente articles Char à voile',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            139 =>
            array(
                'id' => 355,
                'discipline_id' => 311,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            140 =>
            array(
                'id' => 356,
                'discipline_id' => 311,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stages',
                'nom_categorie_client' => 'Stages',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            141 =>
            array(
                'id' => 357,
                'discipline_id' => 311,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particluliers',
                'nom_categorie_client' => 'Cours particluliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            142 =>
            array(
                'id' => 358,
                'discipline_id' => 311,
                'categorie_id' => 37,
                'nom_categorie_pro' => 'Prestations',
                'nom_categorie_client' => 'Prestations',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            143 =>
            array(
                'id' => 359,
                'discipline_id' => 311,
                'categorie_id' => 38,
                'nom_categorie_pro' => 'Spectacles',
                'nom_categorie_client' => 'Spectacles',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            144 =>
            array(
                'id' => 360,
                'discipline_id' => 311,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Concours',
                'nom_categorie_client' => 'Concours',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            145 =>
            array(
                'id' => 361,
                'discipline_id' => 311,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles Cheerleading',
                'nom_categorie_client' => 'Vente articles Cheerleading',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            146 =>
            array(
                'id' => 362,
                'discipline_id' => 167,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Apprendre à dresser',
                'nom_categorie_client' => 'Apprendre à dresser',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            147 =>
            array(
                'id' => 363,
                'discipline_id' => 167,
                'categorie_id' => 32,
                'nom_categorie_pro' => 'Stage dressage',
                'nom_categorie_client' => 'Stage dressage',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            148 =>
            array(
                'id' => 364,
                'discipline_id' => 167,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Cours particuliers',
                'nom_categorie_client' => 'Cours particuliers',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            149 =>
            array(
                'id' => 365,
                'discipline_id' => 167,
                'categorie_id' => 37,
                'nom_categorie_pro' => 'Dressage',
                'nom_categorie_client' => 'Dressage',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            150 =>
            array(
                'id' => 366,
                'discipline_id' => 167,
                'categorie_id' => 38,
                'nom_categorie_pro' => 'Démonstrations',
                'nom_categorie_client' => 'Démonstrations',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            151 =>
            array(
                'id' => 367,
                'discipline_id' => 167,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Concours canins',
                'nom_categorie_client' => 'Concours canins',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            152 =>
            array(
                'id' => 368,
                'discipline_id' => 167,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles dressage',
                'nom_categorie_client' => 'Vente articles dressage',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            153 =>
            array(
                'id' => 369,
                'discipline_id' => 278,
                'categorie_id' => 30,
                'nom_categorie_pro' => 'Cours collectifs',
                'nom_categorie_client' => 'Cours collectifs',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            154 =>
            array(
                'id' => 370,
                'discipline_id' => 278,
                'categorie_id' => 33,
                'nom_categorie_pro' => 'Coaching',
                'nom_categorie_client' => 'Coaching',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            155 =>
            array(
                'id' => 371,
                'discipline_id' => 278,
                'categorie_id' => 34,
                'nom_categorie_pro' => 'Pratique en salle de sport',
                'nom_categorie_client' => 'Pratique en salle de sport',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            156 =>
            array(
                'id' => 372,
                'discipline_id' => 278,
                'categorie_id' => 37,
                'nom_categorie_pro' => 'Prestations',
                'nom_categorie_client' => 'Prestations',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            157 =>
            array(
                'id' => 373,
                'discipline_id' => 278,
                'categorie_id' => 39,
                'nom_categorie_pro' => 'Concours',
                'nom_categorie_client' => 'Concours',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            158 =>
            array(
                'id' => 374,
                'discipline_id' => 278,
                'categorie_id' => 40,
                'nom_categorie_pro' => 'Vente articles Musculation',
                'nom_categorie_client' => 'Vente articles Musculation',
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
        ));


    }
}
