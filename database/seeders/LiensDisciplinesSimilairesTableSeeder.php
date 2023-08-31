<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LiensDisciplinesSimilairesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('liens_disciplines_similaires')->delete();

        DB::table('liens_disciplines_similaires')->insert(array(
            0 =>
            array(
                'id' => 1,
                'discipline_id' => 1,
                'discipline_similaire_id' => 20,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            1 =>
            array(
                'id' => 2,
                'discipline_id' => 1,
                'discipline_similaire_id' => 26,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            2 =>
            array(
                'id' => 3,
                'discipline_id' => 1,
                'discipline_similaire_id' => 28,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            3 =>
            array(
                'id' => 4,
                'discipline_id' => 1,
                'discipline_similaire_id' => 29,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            4 =>
            array(
                'id' => 5,
                'discipline_id' => 2,
                'discipline_similaire_id' => 8,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            5 =>
            array(
                'id' => 6,
                'discipline_id' => 2,
                'discipline_similaire_id' => 25,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            6 =>
            array(
                'id' => 7,
                'discipline_id' => 3,
                'discipline_similaire_id' => 12,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            7 =>
            array(
                'id' => 8,
                'discipline_id' => 4,
                'discipline_similaire_id' => 5,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            8 =>
            array(
                'id' => 9,
                'discipline_id' => 4,
                'discipline_similaire_id' => 7,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            9 =>
            array(
                'id' => 10,
                'discipline_id' => 4,
                'discipline_similaire_id' => 9,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            10 =>
            array(
                'id' => 11,
                'discipline_id' => 4,
                'discipline_similaire_id' => 24,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            11 =>
            array(
                'id' => 12,
                'discipline_id' => 5,
                'discipline_similaire_id' => 4,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            12 =>
            array(
                'id' => 13,
                'discipline_id' => 5,
                'discipline_similaire_id' => 30,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            13 =>
            array(
                'id' => 14,
                'discipline_id' => 6,
                'discipline_similaire_id' => 8,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            14 =>
            array(
                'id' => 15,
                'discipline_id' => 6,
                'discipline_similaire_id' => 23,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            15 =>
            array(
                'id' => 16,
                'discipline_id' => 7,
                'discipline_similaire_id' => 4,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            16 =>
            array(
                'id' => 17,
                'discipline_id' => 7,
                'discipline_similaire_id' => 9,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            17 =>
            array(
                'id' => 18,
                'discipline_id' => 8,
                'discipline_similaire_id' => 2,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            18 =>
            array(
                'id' => 19,
                'discipline_id' => 8,
                'discipline_similaire_id' => 6,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            19 =>
            array(
                'id' => 20,
                'discipline_id' => 8,
                'discipline_similaire_id' => 23,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            20 =>
            array(
                'id' => 21,
                'discipline_id' => 9,
                'discipline_similaire_id' => 4,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            21 =>
            array(
                'id' => 22,
                'discipline_id' => 9,
                'discipline_similaire_id' => 7,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            22 =>
            array(
                'id' => 23,
                'discipline_id' => 9,
                'discipline_similaire_id' => 255,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            23 =>
            array(
                'id' => 24,
                'discipline_id' => 7,
                'discipline_similaire_id' => 255,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            24 =>
            array(
                'id' => 25,
                'discipline_id' => 4,
                'discipline_similaire_id' => 255,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            25 =>
            array(
                'id' => 26,
                'discipline_id' => 10,
                'discipline_similaire_id' => 256,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            26 =>
            array(
                'id' => 27,
                'discipline_id' => 10,
                'discipline_similaire_id' => 169,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            27 =>
            array(
                'id' => 28,
                'discipline_id' => 11,
                'discipline_similaire_id' => 170,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            28 =>
            array(
                'id' => 29,
                'discipline_id' => 11,
                'discipline_similaire_id' => 169,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            29 =>
            array(
                'id' => 30,
                'discipline_id' => 12,
                'discipline_similaire_id' => 3,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            30 =>
            array(
                'id' => 31,
                'discipline_id' => 12,
                'discipline_similaire_id' => 24,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            31 =>
            array(
                'id' => 32,
                'discipline_id' => 13,
                'discipline_similaire_id' => 14,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            32 =>
            array(
                'id' => 33,
                'discipline_id' => 13,
                'discipline_similaire_id' => 22,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            33 =>
            array(
                'id' => 34,
                'discipline_id' => 14,
                'discipline_similaire_id' => 13,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            34 =>
            array(
                'id' => 35,
                'discipline_id' => 14,
                'discipline_similaire_id' => 22,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            35 =>
            array(
                'id' => 36,
                'discipline_id' => 15,
                'discipline_similaire_id' => 103,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            36 =>
            array(
                'id' => 38,
                'discipline_id' => 15,
                'discipline_similaire_id' => 108,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            37 =>
            array(
                'id' => 39,
                'discipline_id' => 15,
                'discipline_similaire_id' => 175,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            38 =>
            array(
                'id' => 40,
                'discipline_id' => 15,
                'discipline_similaire_id' => 120,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            39 =>
            array(
                'id' => 41,
                'discipline_id' => 16,
                'discipline_similaire_id' => 4,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            40 =>
            array(
                'id' => 42,
                'discipline_id' => 16,
                'discipline_similaire_id' => 7,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            41 =>
            array(
                'id' => 43,
                'discipline_id' => 16,
                'discipline_similaire_id' => 9,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            42 =>
            array(
                'id' => 44,
                'discipline_id' => 17,
                'discipline_similaire_id' => 12,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            43 =>
            array(
                'id' => 45,
                'discipline_id' => 17,
                'discipline_similaire_id' => 30,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            44 =>
            array(
                'id' => 46,
                'discipline_id' => 18,
                'discipline_similaire_id' => 1,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            45 =>
            array(
                'id' => 47,
                'discipline_id' => 18,
                'discipline_similaire_id' => 19,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            46 =>
            array(
                'id' => 48,
                'discipline_id' => 18,
                'discipline_similaire_id' => 26,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            47 =>
            array(
                'id' => 49,
                'discipline_id' => 18,
                'discipline_similaire_id' => 28,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            48 =>
            array(
                'id' => 50,
                'discipline_id' => 19,
                'discipline_similaire_id' => 18,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            49 =>
            array(
                'id' => 51,
                'discipline_id' => 19,
                'discipline_similaire_id' => 26,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            50 =>
            array(
                'id' => 52,
                'discipline_id' => 20,
                'discipline_similaire_id' => 1,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            51 =>
            array(
                'id' => 53,
                'discipline_id' => 20,
                'discipline_similaire_id' => 30,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            52 =>
            array(
                'id' => 54,
                'discipline_id' => 21,
                'discipline_similaire_id' => 170,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            53 =>
            array(
                'id' => 55,
                'discipline_id' => 21,
                'discipline_similaire_id' => 10,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            54 =>
            array(
                'id' => 56,
                'discipline_id' => 21,
                'discipline_similaire_id' => 11,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            55 =>
            array(
                'id' => 57,
                'discipline_id' => 22,
                'discipline_similaire_id' => 13,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            56 =>
            array(
                'id' => 58,
                'discipline_id' => 22,
                'discipline_similaire_id' => 14,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            57 =>
            array(
                'id' => 59,
                'discipline_id' => 23,
                'discipline_similaire_id' => 6,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            58 =>
            array(
                'id' => 60,
                'discipline_id' => 23,
                'discipline_similaire_id' => 8,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            59 =>
            array(
                'id' => 61,
                'discipline_id' => 24,
                'discipline_similaire_id' => 4,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            60 =>
            array(
                'id' => 62,
                'discipline_id' => 24,
                'discipline_similaire_id' => 5,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            61 =>
            array(
                'id' => 63,
                'discipline_id' => 24,
                'discipline_similaire_id' => 12,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            62 =>
            array(
                'id' => 64,
                'discipline_id' => 25,
                'discipline_similaire_id' => 2,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            63 =>
            array(
                'id' => 65,
                'discipline_id' => 25,
                'discipline_similaire_id' => 8,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            64 =>
            array(
                'id' => 66,
                'discipline_id' => 26,
                'discipline_similaire_id' => 1,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            65 =>
            array(
                'id' => 67,
                'discipline_id' => 26,
                'discipline_similaire_id' => 18,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            66 =>
            array(
                'id' => 68,
                'discipline_id' => 26,
                'discipline_similaire_id' => 19,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            67 =>
            array(
                'id' => 69,
                'discipline_id' => 26,
                'discipline_similaire_id' => 28,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            68 =>
            array(
                'id' => 70,
                'discipline_id' => 27,
                'discipline_similaire_id' => 12,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            69 =>
            array(
                'id' => 71,
                'discipline_id' => 27,
                'discipline_similaire_id' => 24,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            70 =>
            array(
                'id' => 72,
                'discipline_id' => 28,
                'discipline_similaire_id' => 1,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            71 =>
            array(
                'id' => 73,
                'discipline_id' => 28,
                'discipline_similaire_id' => 18,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            72 =>
            array(
                'id' => 74,
                'discipline_id' => 28,
                'discipline_similaire_id' => 26,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            73 =>
            array(
                'id' => 75,
                'discipline_id' => 28,
                'discipline_similaire_id' => 29,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            74 =>
            array(
                'id' => 76,
                'discipline_id' => 29,
                'discipline_similaire_id' => 1,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            75 =>
            array(
                'id' => 77,
                'discipline_id' => 29,
                'discipline_similaire_id' => 26,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            76 =>
            array(
                'id' => 78,
                'discipline_id' => 29,
                'discipline_similaire_id' => 28,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            77 =>
            array(
                'id' => 79,
                'discipline_id' => 30,
                'discipline_similaire_id' => 1,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            78 =>
            array(
                'id' => 80,
                'discipline_id' => 30,
                'discipline_similaire_id' => 5,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            79 =>
            array(
                'id' => 81,
                'discipline_id' => 30,
                'discipline_similaire_id' => 12,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            80 =>
            array(
                'id' => 82,
                'discipline_id' => 31,
                'discipline_similaire_id' => 103,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            81 =>
            array(
                'id' => 83,
                'discipline_id' => 31,
                'discipline_similaire_id' => 12,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            82 =>
            array(
                'id' => 84,
                'discipline_id' => 31,
                'discipline_similaire_id' => 15,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            83 =>
            array(
                'id' => 85,
                'discipline_id' => 31,
                'discipline_similaire_id' => 108,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            84 =>
            array(
                'id' => 86,
                'discipline_id' => 31,
                'discipline_similaire_id' => 175,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            85 =>
            array(
                'id' => 87,
                'discipline_id' => 32,
                'discipline_similaire_id' => 289,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            86 =>
            array(
                'id' => 88,
                'discipline_id' => 32,
                'discipline_similaire_id' => 45,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            87 =>
            array(
                'id' => 89,
                'discipline_id' => 32,
                'discipline_similaire_id' => 48,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            88 =>
            array(
                'id' => 90,
                'discipline_id' => 32,
                'discipline_similaire_id' => 50,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            89 =>
            array(
                'id' => 91,
                'discipline_id' => 32,
                'discipline_similaire_id' => 51,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            90 =>
            array(
                'id' => 92,
                'discipline_id' => 32,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            91 =>
            array(
                'id' => 93,
                'discipline_id' => 32,
                'discipline_similaire_id' => 54,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            92 =>
            array(
                'id' => 94,
                'discipline_id' => 32,
                'discipline_similaire_id' => 55,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            93 =>
            array(
                'id' => 95,
                'discipline_id' => 32,
                'discipline_similaire_id' => 56,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            94 =>
            array(
                'id' => 96,
                'discipline_id' => 32,
                'discipline_similaire_id' => 67,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            95 =>
            array(
                'id' => 97,
                'discipline_id' => 32,
                'discipline_similaire_id' => 72,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            96 =>
            array(
                'id' => 98,
                'discipline_id' => 32,
                'discipline_similaire_id' => 73,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            97 =>
            array(
                'id' => 99,
                'discipline_id' => 32,
                'discipline_similaire_id' => 75,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            98 =>
            array(
                'id' => 100,
                'discipline_id' => 32,
                'discipline_similaire_id' => 77,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            99 =>
            array(
                'id' => 101,
                'discipline_id' => 32,
                'discipline_similaire_id' => 79,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            100 =>
            array(
                'id' => 102,
                'discipline_id' => 33,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            101 =>
            array(
                'id' => 103,
                'discipline_id' => 33,
                'discipline_similaire_id' => 289,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            102 =>
            array(
                'id' => 104,
                'discipline_id' => 33,
                'discipline_similaire_id' => 38,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            103 =>
            array(
                'id' => 105,
                'discipline_id' => 33,
                'discipline_similaire_id' => 40,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            104 =>
            array(
                'id' => 106,
                'discipline_id' => 33,
                'discipline_similaire_id' => 45,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            105 =>
            array(
                'id' => 107,
                'discipline_id' => 33,
                'discipline_similaire_id' => 46,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            106 =>
            array(
                'id' => 108,
                'discipline_id' => 33,
                'discipline_similaire_id' => 47,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            107 =>
            array(
                'id' => 109,
                'discipline_id' => 33,
                'discipline_similaire_id' => 48,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            108 =>
            array(
                'id' => 110,
                'discipline_id' => 33,
                'discipline_similaire_id' => 52,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            109 =>
            array(
                'id' => 111,
                'discipline_id' => 33,
                'discipline_similaire_id' => 54,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            110 =>
            array(
                'id' => 112,
                'discipline_id' => 33,
                'discipline_similaire_id' => 56,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            111 =>
            array(
                'id' => 113,
                'discipline_id' => 33,
                'discipline_similaire_id' => 60,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            112 =>
            array(
                'id' => 114,
                'discipline_id' => 33,
                'discipline_similaire_id' => 67,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            113 =>
            array(
                'id' => 115,
                'discipline_id' => 34,
                'discipline_similaire_id' => 35,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            114 =>
            array(
                'id' => 116,
                'discipline_id' => 34,
                'discipline_similaire_id' => 36,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            115 =>
            array(
                'id' => 117,
                'discipline_id' => 34,
                'discipline_similaire_id' => 37,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            116 =>
            array(
                'id' => 118,
                'discipline_id' => 34,
                'discipline_similaire_id' => 42,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            117 =>
            array(
                'id' => 119,
                'discipline_id' => 34,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            118 =>
            array(
                'id' => 120,
                'discipline_id' => 34,
                'discipline_similaire_id' => 58,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            119 =>
            array(
                'id' => 121,
                'discipline_id' => 34,
                'discipline_similaire_id' => 59,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            120 =>
            array(
                'id' => 122,
                'discipline_id' => 34,
                'discipline_similaire_id' => 73,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            121 =>
            array(
                'id' => 123,
                'discipline_id' => 35,
                'discipline_similaire_id' => 36,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            122 =>
            array(
                'id' => 124,
                'discipline_id' => 35,
                'discipline_similaire_id' => 34,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            123 =>
            array(
                'id' => 125,
                'discipline_id' => 35,
                'discipline_similaire_id' => 37,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            124 =>
            array(
                'id' => 126,
                'discipline_id' => 35,
                'discipline_similaire_id' => 42,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            125 =>
            array(
                'id' => 127,
                'discipline_id' => 35,
                'discipline_similaire_id' => 57,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            126 =>
            array(
                'id' => 128,
                'discipline_id' => 35,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            127 =>
            array(
                'id' => 129,
                'discipline_id' => 35,
                'discipline_similaire_id' => 59,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            128 =>
            array(
                'id' => 130,
                'discipline_id' => 35,
                'discipline_similaire_id' => 73,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            129 =>
            array(
                'id' => 131,
                'discipline_id' => 36,
                'discipline_similaire_id' => 35,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            130 =>
            array(
                'id' => 132,
                'discipline_id' => 36,
                'discipline_similaire_id' => 34,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            131 =>
            array(
                'id' => 133,
                'discipline_id' => 36,
                'discipline_similaire_id' => 37,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            132 =>
            array(
                'id' => 134,
                'discipline_id' => 36,
                'discipline_similaire_id' => 42,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            133 =>
            array(
                'id' => 135,
                'discipline_id' => 36,
                'discipline_similaire_id' => 57,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            134 =>
            array(
                'id' => 136,
                'discipline_id' => 36,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            135 =>
            array(
                'id' => 137,
                'discipline_id' => 36,
                'discipline_similaire_id' => 59,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            136 =>
            array(
                'id' => 138,
                'discipline_id' => 36,
                'discipline_similaire_id' => 73,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            137 =>
            array(
                'id' => 139,
                'discipline_id' => 37,
                'discipline_similaire_id' => 35,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            138 =>
            array(
                'id' => 140,
                'discipline_id' => 37,
                'discipline_similaire_id' => 36,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            139 =>
            array(
                'id' => 141,
                'discipline_id' => 37,
                'discipline_similaire_id' => 34,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            140 =>
            array(
                'id' => 142,
                'discipline_id' => 37,
                'discipline_similaire_id' => 42,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            141 =>
            array(
                'id' => 143,
                'discipline_id' => 37,
                'discipline_similaire_id' => 57,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            142 =>
            array(
                'id' => 144,
                'discipline_id' => 37,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            143 =>
            array(
                'id' => 145,
                'discipline_id' => 38,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            144 =>
            array(
                'id' => 146,
                'discipline_id' => 38,
                'discipline_similaire_id' => 33,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            145 =>
            array(
                'id' => 147,
                'discipline_id' => 38,
                'discipline_similaire_id' => 280,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            146 =>
            array(
                'id' => 148,
                'discipline_id' => 38,
                'discipline_similaire_id' => 40,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            147 =>
            array(
                'id' => 149,
                'discipline_id' => 38,
                'discipline_similaire_id' => 45,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            148 =>
            array(
                'id' => 150,
                'discipline_id' => 38,
                'discipline_similaire_id' => 46,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            149 =>
            array(
                'id' => 151,
                'discipline_id' => 38,
                'discipline_similaire_id' => 47,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            150 =>
            array(
                'id' => 152,
                'discipline_id' => 38,
                'discipline_similaire_id' => 48,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            151 =>
            array(
                'id' => 153,
                'discipline_id' => 38,
                'discipline_similaire_id' => 52,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            152 =>
            array(
                'id' => 154,
                'discipline_id' => 39,
                'discipline_similaire_id' => 185,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            153 =>
            array(
                'id' => 155,
                'discipline_id' => 39,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            154 =>
            array(
                'id' => 156,
                'discipline_id' => 39,
                'discipline_similaire_id' => 66,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            155 =>
            array(
                'id' => 157,
                'discipline_id' => 39,
                'discipline_similaire_id' => 191,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            156 =>
            array(
                'id' => 158,
                'discipline_id' => 39,
                'discipline_similaire_id' => 69,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            157 =>
            array(
                'id' => 159,
                'discipline_id' => 39,
                'discipline_similaire_id' => 274,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            158 =>
            array(
                'id' => 160,
                'discipline_id' => 40,
                'discipline_similaire_id' => 33,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            159 =>
            array(
                'id' => 161,
                'discipline_id' => 40,
                'discipline_similaire_id' => 280,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            160 =>
            array(
                'id' => 162,
                'discipline_id' => 40,
                'discipline_similaire_id' => 38,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            161 =>
            array(
                'id' => 163,
                'discipline_id' => 40,
                'discipline_similaire_id' => 46,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            162 =>
            array(
                'id' => 164,
                'discipline_id' => 40,
                'discipline_similaire_id' => 52,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            163 =>
            array(
                'id' => 165,
                'discipline_id' => 40,
                'discipline_similaire_id' => 54,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            164 =>
            array(
                'id' => 166,
                'discipline_id' => 40,
                'discipline_similaire_id' => 55,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            165 =>
            array(
                'id' => 167,
                'discipline_id' => 40,
                'discipline_similaire_id' => 56,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            166 =>
            array(
                'id' => 168,
                'discipline_id' => 40,
                'discipline_similaire_id' => 62,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            167 =>
            array(
                'id' => 169,
                'discipline_id' => 40,
                'discipline_similaire_id' => 69,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            168 =>
            array(
                'id' => 170,
                'discipline_id' => 40,
                'discipline_similaire_id' => 79,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            169 =>
            array(
                'id' => 171,
                'discipline_id' => 37,
                'discipline_similaire_id' => 65,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            170 =>
            array(
                'id' => 172,
                'discipline_id' => 41,
                'discipline_similaire_id' => 289,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            171 =>
            array(
                'id' => 173,
                'discipline_id' => 41,
                'discipline_similaire_id' => 280,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            172 =>
            array(
                'id' => 174,
                'discipline_id' => 41,
                'discipline_similaire_id' => 40,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            173 =>
            array(
                'id' => 175,
                'discipline_id' => 41,
                'discipline_similaire_id' => 46,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            174 =>
            array(
                'id' => 176,
                'discipline_id' => 41,
                'discipline_similaire_id' => 52,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            175 =>
            array(
                'id' => 177,
                'discipline_id' => 41,
                'discipline_similaire_id' => 54,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            176 =>
            array(
                'id' => 178,
                'discipline_id' => 41,
                'discipline_similaire_id' => 56,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            177 =>
            array(
                'id' => 179,
                'discipline_id' => 41,
                'discipline_similaire_id' => 62,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            178 =>
            array(
                'id' => 180,
                'discipline_id' => 42,
                'discipline_similaire_id' => 35,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            179 =>
            array(
                'id' => 181,
                'discipline_id' => 42,
                'discipline_similaire_id' => 36,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            180 =>
            array(
                'id' => 182,
                'discipline_id' => 42,
                'discipline_similaire_id' => 34,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            181 =>
            array(
                'id' => 183,
                'discipline_id' => 42,
                'discipline_similaire_id' => 37,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            182 =>
            array(
                'id' => 184,
                'discipline_id' => 42,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            183 =>
            array(
                'id' => 185,
                'discipline_id' => 42,
                'discipline_similaire_id' => 59,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            184 =>
            array(
                'id' => 186,
                'discipline_id' => 42,
                'discipline_similaire_id' => 65,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            185 =>
            array(
                'id' => 187,
                'discipline_id' => 43,
                'discipline_similaire_id' => 290,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            186 =>
            array(
                'id' => 188,
                'discipline_id' => 43,
                'discipline_similaire_id' => 44,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            187 =>
            array(
                'id' => 189,
                'discipline_id' => 43,
                'discipline_similaire_id' => 49,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            188 =>
            array(
                'id' => 190,
                'discipline_id' => 43,
                'discipline_similaire_id' => 61,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            189 =>
            array(
                'id' => 191,
                'discipline_id' => 43,
                'discipline_similaire_id' => 71,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            190 =>
            array(
                'id' => 192,
                'discipline_id' => 44,
                'discipline_similaire_id' => 290,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            191 =>
            array(
                'id' => 193,
                'discipline_id' => 44,
                'discipline_similaire_id' => 43,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            192 =>
            array(
                'id' => 194,
                'discipline_id' => 44,
                'discipline_similaire_id' => 61,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            193 =>
            array(
                'id' => 195,
                'discipline_id' => 44,
                'discipline_similaire_id' => 71,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            194 =>
            array(
                'id' => 196,
                'discipline_id' => 45,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            195 =>
            array(
                'id' => 197,
                'discipline_id' => 45,
                'discipline_similaire_id' => 49,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            196 =>
            array(
                'id' => 198,
                'discipline_id' => 45,
                'discipline_similaire_id' => 50,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            197 =>
            array(
                'id' => 199,
                'discipline_id' => 45,
                'discipline_similaire_id' => 51,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            198 =>
            array(
                'id' => 200,
                'discipline_id' => 45,
                'discipline_similaire_id' => 69,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            199 =>
            array(
                'id' => 201,
                'discipline_id' => 46,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            200 =>
            array(
                'id' => 202,
                'discipline_id' => 46,
                'discipline_similaire_id' => 289,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            201 =>
            array(
                'id' => 203,
                'discipline_id' => 46,
                'discipline_similaire_id' => 280,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            202 =>
            array(
                'id' => 204,
                'discipline_id' => 46,
                'discipline_similaire_id' => 40,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            203 =>
            array(
                'id' => 205,
                'discipline_id' => 46,
                'discipline_similaire_id' => 52,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            204 =>
            array(
                'id' => 206,
                'discipline_id' => 46,
                'discipline_similaire_id' => 54,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            205 =>
            array(
                'id' => 207,
                'discipline_id' => 46,
                'discipline_similaire_id' => 55,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            206 =>
            array(
                'id' => 208,
                'discipline_id' => 46,
                'discipline_similaire_id' => 56,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            207 =>
            array(
                'id' => 209,
                'discipline_id' => 46,
                'discipline_similaire_id' => 62,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            208 =>
            array(
                'id' => 210,
                'discipline_id' => 46,
                'discipline_similaire_id' => 78,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            209 =>
            array(
                'id' => 211,
                'discipline_id' => 47,
                'discipline_similaire_id' => 36,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            210 =>
            array(
                'id' => 212,
                'discipline_id' => 47,
                'discipline_similaire_id' => 59,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            211 =>
            array(
                'id' => 213,
                'discipline_id' => 47,
                'discipline_similaire_id' => 63,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            212 =>
            array(
                'id' => 214,
                'discipline_id' => 47,
                'discipline_similaire_id' => 74,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            213 =>
            array(
                'id' => 215,
                'discipline_id' => 47,
                'discipline_similaire_id' => 77,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            214 =>
            array(
                'id' => 216,
                'discipline_id' => 48,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            215 =>
            array(
                'id' => 217,
                'discipline_id' => 48,
                'discipline_similaire_id' => 33,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            216 =>
            array(
                'id' => 218,
                'discipline_id' => 48,
                'discipline_similaire_id' => 289,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            217 =>
            array(
                'id' => 219,
                'discipline_id' => 48,
                'discipline_similaire_id' => 280,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            218 =>
            array(
                'id' => 220,
                'discipline_id' => 48,
                'discipline_similaire_id' => 38,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            219 =>
            array(
                'id' => 221,
                'discipline_id' => 48,
                'discipline_similaire_id' => 40,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            220 =>
            array(
                'id' => 222,
                'discipline_id' => 48,
                'discipline_similaire_id' => 46,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            221 =>
            array(
                'id' => 223,
                'discipline_id' => 48,
                'discipline_similaire_id' => 52,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            222 =>
            array(
                'id' => 224,
                'discipline_id' => 48,
                'discipline_similaire_id' => 54,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            223 =>
            array(
                'id' => 225,
                'discipline_id' => 48,
                'discipline_similaire_id' => 56,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            224 =>
            array(
                'id' => 226,
                'discipline_id' => 50,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            225 =>
            array(
                'id' => 227,
                'discipline_id' => 50,
                'discipline_similaire_id' => 45,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            226 =>
            array(
                'id' => 228,
                'discipline_id' => 50,
                'discipline_similaire_id' => 49,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            227 =>
            array(
                'id' => 229,
                'discipline_id' => 50,
                'discipline_similaire_id' => 51,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            228 =>
            array(
                'id' => 230,
                'discipline_id' => 50,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            229 =>
            array(
                'id' => 231,
                'discipline_id' => 50,
                'discipline_similaire_id' => 58,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            230 =>
            array(
                'id' => 232,
                'discipline_id' => 50,
                'discipline_similaire_id' => 63,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            231 =>
            array(
                'id' => 233,
                'discipline_id' => 50,
                'discipline_similaire_id' => 69,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            232 =>
            array(
                'id' => 234,
                'discipline_id' => 50,
                'discipline_similaire_id' => 72,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            233 =>
            array(
                'id' => 235,
                'discipline_id' => 50,
                'discipline_similaire_id' => 279,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            234 =>
            array(
                'id' => 236,
                'discipline_id' => 50,
                'discipline_similaire_id' => 75,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            235 =>
            array(
                'id' => 237,
                'discipline_id' => 51,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            236 =>
            array(
                'id' => 238,
                'discipline_id' => 51,
                'discipline_similaire_id' => 45,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            237 =>
            array(
                'id' => 239,
                'discipline_id' => 51,
                'discipline_similaire_id' => 49,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            238 =>
            array(
                'id' => 240,
                'discipline_id' => 51,
                'discipline_similaire_id' => 50,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            239 =>
            array(
                'id' => 241,
                'discipline_id' => 51,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            240 =>
            array(
                'id' => 242,
                'discipline_id' => 51,
                'discipline_similaire_id' => 58,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            241 =>
            array(
                'id' => 243,
                'discipline_id' => 51,
                'discipline_similaire_id' => 63,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            242 =>
            array(
                'id' => 244,
                'discipline_id' => 51,
                'discipline_similaire_id' => 69,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            243 =>
            array(
                'id' => 245,
                'discipline_id' => 51,
                'discipline_similaire_id' => 72,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            244 =>
            array(
                'id' => 246,
                'discipline_id' => 51,
                'discipline_similaire_id' => 279,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            245 =>
            array(
                'id' => 247,
                'discipline_id' => 51,
                'discipline_similaire_id' => 75,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            246 =>
            array(
                'id' => 248,
                'discipline_id' => 52,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            247 =>
            array(
                'id' => 249,
                'discipline_id' => 52,
                'discipline_similaire_id' => 33,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            248 =>
            array(
                'id' => 250,
                'discipline_id' => 52,
                'discipline_similaire_id' => 289,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            249 =>
            array(
                'id' => 251,
                'discipline_id' => 52,
                'discipline_similaire_id' => 280,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            250 =>
            array(
                'id' => 252,
                'discipline_id' => 52,
                'discipline_similaire_id' => 38,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            251 =>
            array(
                'id' => 253,
                'discipline_id' => 52,
                'discipline_similaire_id' => 40,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            252 =>
            array(
                'id' => 254,
                'discipline_id' => 52,
                'discipline_similaire_id' => 41,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            253 =>
            array(
                'id' => 255,
                'discipline_id' => 52,
                'discipline_similaire_id' => 46,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            254 =>
            array(
                'id' => 256,
                'discipline_id' => 52,
                'discipline_similaire_id' => 54,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            255 =>
            array(
                'id' => 257,
                'discipline_id' => 52,
                'discipline_similaire_id' => 55,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            256 =>
            array(
                'id' => 258,
                'discipline_id' => 52,
                'discipline_similaire_id' => 56,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            257 =>
            array(
                'id' => 259,
                'discipline_id' => 52,
                'discipline_similaire_id' => 62,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            258 =>
            array(
                'id' => 260,
                'discipline_id' => 52,
                'discipline_similaire_id' => 64,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            259 =>
            array(
                'id' => 261,
                'discipline_id' => 53,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            260 =>
            array(
                'id' => 262,
                'discipline_id' => 53,
                'discipline_similaire_id' => 35,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            261 =>
            array(
                'id' => 263,
                'discipline_id' => 53,
                'discipline_similaire_id' => 49,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            262 =>
            array(
                'id' => 264,
                'discipline_id' => 53,
                'discipline_similaire_id' => 58,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            263 =>
            array(
                'id' => 265,
                'discipline_id' => 53,
                'discipline_similaire_id' => 59,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            264 =>
            array(
                'id' => 266,
                'discipline_id' => 53,
                'discipline_similaire_id' => 72,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            265 =>
            array(
                'id' => 267,
                'discipline_id' => 53,
                'discipline_similaire_id' => 73,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            266 =>
            array(
                'id' => 268,
                'discipline_id' => 54,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            267 =>
            array(
                'id' => 269,
                'discipline_id' => 54,
                'discipline_similaire_id' => 33,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            268 =>
            array(
                'id' => 270,
                'discipline_id' => 54,
                'discipline_similaire_id' => 289,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            269 =>
            array(
                'id' => 271,
                'discipline_id' => 54,
                'discipline_similaire_id' => 280,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            270 =>
            array(
                'id' => 272,
                'discipline_id' => 54,
                'discipline_similaire_id' => 38,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            271 =>
            array(
                'id' => 273,
                'discipline_id' => 54,
                'discipline_similaire_id' => 40,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            272 =>
            array(
                'id' => 274,
                'discipline_id' => 54,
                'discipline_similaire_id' => 46,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            273 =>
            array(
                'id' => 275,
                'discipline_id' => 54,
                'discipline_similaire_id' => 52,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            274 =>
            array(
                'id' => 276,
                'discipline_id' => 54,
                'discipline_similaire_id' => 55,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            275 =>
            array(
                'id' => 277,
                'discipline_id' => 54,
                'discipline_similaire_id' => 56,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            276 =>
            array(
                'id' => 278,
                'discipline_id' => 54,
                'discipline_similaire_id' => 62,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            277 =>
            array(
                'id' => 279,
                'discipline_id' => 54,
                'discipline_similaire_id' => 69,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            278 =>
            array(
                'id' => 280,
                'discipline_id' => 54,
                'discipline_similaire_id' => 75,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            279 =>
            array(
                'id' => 281,
                'discipline_id' => 55,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            280 =>
            array(
                'id' => 282,
                'discipline_id' => 55,
                'discipline_similaire_id' => 33,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            281 =>
            array(
                'id' => 283,
                'discipline_id' => 55,
                'discipline_similaire_id' => 289,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            282 =>
            array(
                'id' => 284,
                'discipline_id' => 55,
                'discipline_similaire_id' => 280,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            283 =>
            array(
                'id' => 285,
                'discipline_id' => 55,
                'discipline_similaire_id' => 38,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            284 =>
            array(
                'id' => 286,
                'discipline_id' => 55,
                'discipline_similaire_id' => 40,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            285 =>
            array(
                'id' => 287,
                'discipline_id' => 55,
                'discipline_similaire_id' => 46,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            286 =>
            array(
                'id' => 288,
                'discipline_id' => 55,
                'discipline_similaire_id' => 52,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            287 =>
            array(
                'id' => 289,
                'discipline_id' => 55,
                'discipline_similaire_id' => 54,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            288 =>
            array(
                'id' => 290,
                'discipline_id' => 55,
                'discipline_similaire_id' => 56,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            289 =>
            array(
                'id' => 291,
                'discipline_id' => 55,
                'discipline_similaire_id' => 62,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            290 =>
            array(
                'id' => 292,
                'discipline_id' => 56,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            291 =>
            array(
                'id' => 293,
                'discipline_id' => 56,
                'discipline_similaire_id' => 33,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            292 =>
            array(
                'id' => 294,
                'discipline_id' => 56,
                'discipline_similaire_id' => 289,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            293 =>
            array(
                'id' => 295,
                'discipline_id' => 56,
                'discipline_similaire_id' => 280,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            294 =>
            array(
                'id' => 296,
                'discipline_id' => 56,
                'discipline_similaire_id' => 38,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            295 =>
            array(
                'id' => 297,
                'discipline_id' => 56,
                'discipline_similaire_id' => 40,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            296 =>
            array(
                'id' => 298,
                'discipline_id' => 56,
                'discipline_similaire_id' => 46,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            297 =>
            array(
                'id' => 299,
                'discipline_id' => 56,
                'discipline_similaire_id' => 62,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            298 =>
            array(
                'id' => 300,
                'discipline_id' => 57,
                'discipline_similaire_id' => 35,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            299 =>
            array(
                'id' => 301,
                'discipline_id' => 57,
                'discipline_similaire_id' => 36,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            300 =>
            array(
                'id' => 302,
                'discipline_id' => 57,
                'discipline_similaire_id' => 34,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            301 =>
            array(
                'id' => 303,
                'discipline_id' => 57,
                'discipline_similaire_id' => 37,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            302 =>
            array(
                'id' => 304,
                'discipline_id' => 57,
                'discipline_similaire_id' => 290,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            303 =>
            array(
                'id' => 305,
                'discipline_id' => 57,
                'discipline_similaire_id' => 42,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            304 =>
            array(
                'id' => 306,
                'discipline_id' => 57,
                'discipline_similaire_id' => 49,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            305 =>
            array(
                'id' => 307,
                'discipline_id' => 57,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            306 =>
            array(
                'id' => 308,
                'discipline_id' => 57,
                'discipline_similaire_id' => 58,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            307 =>
            array(
                'id' => 309,
                'discipline_id' => 57,
                'discipline_similaire_id' => 59,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            308 =>
            array(
                'id' => 310,
                'discipline_id' => 58,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            309 =>
            array(
                'id' => 311,
                'discipline_id' => 58,
                'discipline_similaire_id' => 37,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            310 =>
            array(
                'id' => 312,
                'discipline_id' => 58,
                'discipline_similaire_id' => 50,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            311 =>
            array(
                'id' => 313,
                'discipline_id' => 58,
                'discipline_similaire_id' => 57,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            312 =>
            array(
                'id' => 314,
                'discipline_id' => 58,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            313 =>
            array(
                'id' => 315,
                'discipline_id' => 58,
                'discipline_similaire_id' => 68,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            314 =>
            array(
                'id' => 316,
                'discipline_id' => 58,
                'discipline_similaire_id' => 72,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            315 =>
            array(
                'id' => 317,
                'discipline_id' => 58,
                'discipline_similaire_id' => 279,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            316 =>
            array(
                'id' => 318,
                'discipline_id' => 59,
                'discipline_similaire_id' => 36,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            317 =>
            array(
                'id' => 319,
                'discipline_id' => 59,
                'discipline_similaire_id' => 47,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            318 =>
            array(
                'id' => 320,
                'discipline_id' => 59,
                'discipline_similaire_id' => 63,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            319 =>
            array(
                'id' => 321,
                'discipline_id' => 59,
                'discipline_similaire_id' => 64,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            320 =>
            array(
                'id' => 322,
                'discipline_id' => 59,
                'discipline_similaire_id' => 74,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            321 =>
            array(
                'id' => 323,
                'discipline_id' => 59,
                'discipline_similaire_id' => 77,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            322 =>
            array(
                'id' => 324,
                'discipline_id' => 59,
                'discipline_similaire_id' => 78,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            323 =>
            array(
                'id' => 325,
                'discipline_id' => 60,
                'discipline_similaire_id' => 33,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            324 =>
            array(
                'id' => 326,
                'discipline_id' => 60,
                'discipline_similaire_id' => 38,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            325 =>
            array(
                'id' => 327,
                'discipline_id' => 60,
                'discipline_similaire_id' => 40,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            326 =>
            array(
                'id' => 328,
                'discipline_id' => 60,
                'discipline_similaire_id' => 54,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            327 =>
            array(
                'id' => 329,
                'discipline_id' => 60,
                'discipline_similaire_id' => 56,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            328 =>
            array(
                'id' => 330,
                'discipline_id' => 60,
                'discipline_similaire_id' => 63,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            329 =>
            array(
                'id' => 331,
                'discipline_id' => 60,
                'discipline_similaire_id' => 64,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            330 =>
            array(
                'id' => 332,
                'discipline_id' => 60,
                'discipline_similaire_id' => 274,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            331 =>
            array(
                'id' => 333,
                'discipline_id' => 61,
                'discipline_similaire_id' => 290,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            332 =>
            array(
                'id' => 334,
                'discipline_id' => 61,
                'discipline_similaire_id' => 43,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            333 =>
            array(
                'id' => 335,
                'discipline_id' => 61,
                'discipline_similaire_id' => 44,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            334 =>
            array(
                'id' => 336,
                'discipline_id' => 61,
                'discipline_similaire_id' => 49,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            335 =>
            array(
                'id' => 337,
                'discipline_id' => 61,
                'discipline_similaire_id' => 57,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            336 =>
            array(
                'id' => 338,
                'discipline_id' => 61,
                'discipline_similaire_id' => 69,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            337 =>
            array(
                'id' => 339,
                'discipline_id' => 61,
                'discipline_similaire_id' => 71,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            338 =>
            array(
                'id' => 340,
                'discipline_id' => 62,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            339 =>
            array(
                'id' => 341,
                'discipline_id' => 62,
                'discipline_similaire_id' => 33,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            340 =>
            array(
                'id' => 342,
                'discipline_id' => 62,
                'discipline_similaire_id' => 289,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            341 =>
            array(
                'id' => 343,
                'discipline_id' => 62,
                'discipline_similaire_id' => 280,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            342 =>
            array(
                'id' => 344,
                'discipline_id' => 62,
                'discipline_similaire_id' => 38,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            343 =>
            array(
                'id' => 345,
                'discipline_id' => 62,
                'discipline_similaire_id' => 40,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            344 =>
            array(
                'id' => 346,
                'discipline_id' => 62,
                'discipline_similaire_id' => 46,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            345 =>
            array(
                'id' => 347,
                'discipline_id' => 62,
                'discipline_similaire_id' => 288,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            346 =>
            array(
                'id' => 348,
                'discipline_id' => 62,
                'discipline_similaire_id' => 52,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            347 =>
            array(
                'id' => 349,
                'discipline_id' => 62,
                'discipline_similaire_id' => 56,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            348 =>
            array(
                'id' => 350,
                'discipline_id' => 62,
                'discipline_similaire_id' => 60,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            349 =>
            array(
                'id' => 351,
                'discipline_id' => 63,
                'discipline_similaire_id' => 47,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            350 =>
            array(
                'id' => 352,
                'discipline_id' => 63,
                'discipline_similaire_id' => 59,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            351 =>
            array(
                'id' => 353,
                'discipline_id' => 63,
                'discipline_similaire_id' => 64,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            352 =>
            array(
                'id' => 354,
                'discipline_id' => 63,
                'discipline_similaire_id' => 274,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            353 =>
            array(
                'id' => 355,
                'discipline_id' => 63,
                'discipline_similaire_id' => 74,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            354 =>
            array(
                'id' => 356,
                'discipline_id' => 63,
                'discipline_similaire_id' => 77,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            355 =>
            array(
                'id' => 357,
                'discipline_id' => 63,
                'discipline_similaire_id' => 78,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            356 =>
            array(
                'id' => 358,
                'discipline_id' => 64,
                'discipline_similaire_id' => 289,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            357 =>
            array(
                'id' => 359,
                'discipline_id' => 64,
                'discipline_similaire_id' => 47,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            358 =>
            array(
                'id' => 360,
                'discipline_id' => 64,
                'discipline_similaire_id' => 55,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            359 =>
            array(
                'id' => 361,
                'discipline_id' => 64,
                'discipline_similaire_id' => 59,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            360 =>
            array(
                'id' => 362,
                'discipline_id' => 64,
                'discipline_similaire_id' => 63,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            361 =>
            array(
                'id' => 363,
                'discipline_id' => 64,
                'discipline_similaire_id' => 274,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            362 =>
            array(
                'id' => 364,
                'discipline_id' => 64,
                'discipline_similaire_id' => 78,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            363 =>
            array(
                'id' => 365,
                'discipline_id' => 65,
                'discipline_similaire_id' => 290,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            364 =>
            array(
                'id' => 366,
                'discipline_id' => 65,
                'discipline_similaire_id' => 42,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            365 =>
            array(
                'id' => 367,
                'discipline_id' => 65,
                'discipline_similaire_id' => 43,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            366 =>
            array(
                'id' => 368,
                'discipline_id' => 65,
                'discipline_similaire_id' => 44,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            367 =>
            array(
                'id' => 369,
                'discipline_id' => 65,
                'discipline_similaire_id' => 57,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            368 =>
            array(
                'id' => 370,
                'discipline_id' => 65,
                'discipline_similaire_id' => 61,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            369 =>
            array(
                'id' => 371,
                'discipline_id' => 65,
                'discipline_similaire_id' => 71,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            370 =>
            array(
                'id' => 372,
                'discipline_id' => 66,
                'discipline_similaire_id' => 39,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            371 =>
            array(
                'id' => 373,
                'discipline_id' => 66,
                'discipline_similaire_id' => 288,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            372 =>
            array(
                'id' => 374,
                'discipline_id' => 66,
                'discipline_similaire_id' => 52,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            373 =>
            array(
                'id' => 375,
                'discipline_id' => 66,
                'discipline_similaire_id' => 59,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            374 =>
            array(
                'id' => 376,
                'discipline_id' => 66,
                'discipline_similaire_id' => 74,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            375 =>
            array(
                'id' => 377,
                'discipline_id' => 67,
                'discipline_similaire_id' => 52,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            376 =>
            array(
                'id' => 378,
                'discipline_id' => 67,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            377 =>
            array(
                'id' => 379,
                'discipline_id' => 67,
                'discipline_similaire_id' => 58,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            378 =>
            array(
                'id' => 380,
                'discipline_id' => 67,
                'discipline_similaire_id' => 66,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            379 =>
            array(
                'id' => 381,
                'discipline_id' => 67,
                'discipline_similaire_id' => 279,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            380 =>
            array(
                'id' => 382,
                'discipline_id' => 67,
                'discipline_similaire_id' => 77,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            381 =>
            array(
                'id' => 383,
                'discipline_id' => 67,
                'discipline_similaire_id' => 79,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            382 =>
            array(
                'id' => 384,
                'discipline_id' => 68,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            383 =>
            array(
                'id' => 385,
                'discipline_id' => 68,
                'discipline_similaire_id' => 49,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            384 =>
            array(
                'id' => 386,
                'discipline_id' => 68,
                'discipline_similaire_id' => 50,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            385 =>
            array(
                'id' => 387,
                'discipline_id' => 68,
                'discipline_similaire_id' => 57,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            386 =>
            array(
                'id' => 388,
                'discipline_id' => 68,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            387 =>
            array(
                'id' => 389,
                'discipline_id' => 68,
                'discipline_similaire_id' => 58,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            388 =>
            array(
                'id' => 390,
                'discipline_id' => 68,
                'discipline_similaire_id' => 59,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            389 =>
            array(
                'id' => 391,
                'discipline_id' => 68,
                'discipline_similaire_id' => 72,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            390 =>
            array(
                'id' => 392,
                'discipline_id' => 68,
                'discipline_similaire_id' => 279,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            391 =>
            array(
                'id' => 393,
                'discipline_id' => 69,
                'discipline_similaire_id' => 290,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            392 =>
            array(
                'id' => 394,
                'discipline_id' => 69,
                'discipline_similaire_id' => 43,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            393 =>
            array(
                'id' => 395,
                'discipline_id' => 69,
                'discipline_similaire_id' => 44,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            394 =>
            array(
                'id' => 396,
                'discipline_id' => 69,
                'discipline_similaire_id' => 49,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            395 =>
            array(
                'id' => 397,
                'discipline_id' => 69,
                'discipline_similaire_id' => 61,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            396 =>
            array(
                'id' => 398,
                'discipline_id' => 69,
                'discipline_similaire_id' => 65,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            397 =>
            array(
                'id' => 399,
                'discipline_id' => 70,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            398 =>
            array(
                'id' => 400,
                'discipline_id' => 70,
                'discipline_similaire_id' => 38,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            399 =>
            array(
                'id' => 401,
                'discipline_id' => 70,
                'discipline_similaire_id' => 40,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            400 =>
            array(
                'id' => 402,
                'discipline_id' => 70,
                'discipline_similaire_id' => 59,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            401 =>
            array(
                'id' => 403,
                'discipline_id' => 70,
                'discipline_similaire_id' => 60,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            402 =>
            array(
                'id' => 404,
                'discipline_id' => 70,
                'discipline_similaire_id' => 63,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            403 =>
            array(
                'id' => 405,
                'discipline_id' => 71,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            404 =>
            array(
                'id' => 406,
                'discipline_id' => 71,
                'discipline_similaire_id' => 290,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            405 =>
            array(
                'id' => 407,
                'discipline_id' => 71,
                'discipline_similaire_id' => 43,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            406 =>
            array(
                'id' => 408,
                'discipline_id' => 71,
                'discipline_similaire_id' => 44,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            407 =>
            array(
                'id' => 409,
                'discipline_id' => 71,
                'discipline_similaire_id' => 49,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            408 =>
            array(
                'id' => 410,
                'discipline_id' => 71,
                'discipline_similaire_id' => 61,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            409 =>
            array(
                'id' => 411,
                'discipline_id' => 71,
                'discipline_similaire_id' => 69,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            410 =>
            array(
                'id' => 412,
                'discipline_id' => 72,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            411 =>
            array(
                'id' => 413,
                'discipline_id' => 72,
                'discipline_similaire_id' => 49,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            412 =>
            array(
                'id' => 414,
                'discipline_id' => 72,
                'discipline_similaire_id' => 50,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            413 =>
            array(
                'id' => 415,
                'discipline_id' => 72,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            414 =>
            array(
                'id' => 416,
                'discipline_id' => 72,
                'discipline_similaire_id' => 58,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            415 =>
            array(
                'id' => 417,
                'discipline_id' => 72,
                'discipline_similaire_id' => 279,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            416 =>
            array(
                'id' => 418,
                'discipline_id' => 72,
                'discipline_similaire_id' => 73,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            417 =>
            array(
                'id' => 419,
                'discipline_id' => 73,
                'discipline_similaire_id' => 45,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            418 =>
            array(
                'id' => 420,
                'discipline_id' => 73,
                'discipline_similaire_id' => 49,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            419 =>
            array(
                'id' => 421,
                'discipline_id' => 73,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            420 =>
            array(
                'id' => 422,
                'discipline_id' => 73,
                'discipline_similaire_id' => 58,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            421 =>
            array(
                'id' => 423,
                'discipline_id' => 73,
                'discipline_similaire_id' => 72,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            422 =>
            array(
                'id' => 424,
                'discipline_id' => 74,
                'discipline_similaire_id' => 47,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            423 =>
            array(
                'id' => 425,
                'discipline_id' => 74,
                'discipline_similaire_id' => 59,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            424 =>
            array(
                'id' => 426,
                'discipline_id' => 74,
                'discipline_similaire_id' => 63,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            425 =>
            array(
                'id' => 427,
                'discipline_id' => 74,
                'discipline_similaire_id' => 66,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            426 =>
            array(
                'id' => 428,
                'discipline_id' => 74,
                'discipline_similaire_id' => 276,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            427 =>
            array(
                'id' => 429,
                'discipline_id' => 74,
                'discipline_similaire_id' => 277,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            428 =>
            array(
                'id' => 430,
                'discipline_id' => 75,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            429 =>
            array(
                'id' => 431,
                'discipline_id' => 75,
                'discipline_similaire_id' => 49,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            430 =>
            array(
                'id' => 432,
                'discipline_id' => 75,
                'discipline_similaire_id' => 50,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            431 =>
            array(
                'id' => 433,
                'discipline_id' => 75,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            432 =>
            array(
                'id' => 434,
                'discipline_id' => 75,
                'discipline_similaire_id' => 58,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            433 =>
            array(
                'id' => 435,
                'discipline_id' => 75,
                'discipline_similaire_id' => 77,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            434 =>
            array(
                'id' => 436,
                'discipline_id' => 75,
                'discipline_similaire_id' => 79,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            435 =>
            array(
                'id' => 437,
                'discipline_id' => 76,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            436 =>
            array(
                'id' => 438,
                'discipline_id' => 76,
                'discipline_similaire_id' => 39,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            437 =>
            array(
                'id' => 439,
                'discipline_id' => 76,
                'discipline_similaire_id' => 49,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            438 =>
            array(
                'id' => 440,
                'discipline_id' => 76,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            439 =>
            array(
                'id' => 441,
                'discipline_id' => 76,
                'discipline_similaire_id' => 58,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            440 =>
            array(
                'id' => 442,
                'discipline_id' => 76,
                'discipline_similaire_id' => 72,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            441 =>
            array(
                'id' => 443,
                'discipline_id' => 76,
                'discipline_similaire_id' => 279,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            442 =>
            array(
                'id' => 444,
                'discipline_id' => 77,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            443 =>
            array(
                'id' => 445,
                'discipline_id' => 77,
                'discipline_similaire_id' => 49,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            444 =>
            array(
                'id' => 446,
                'discipline_id' => 77,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            445 =>
            array(
                'id' => 447,
                'discipline_id' => 77,
                'discipline_similaire_id' => 72,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            446 =>
            array(
                'id' => 448,
                'discipline_id' => 77,
                'discipline_similaire_id' => 76,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            447 =>
            array(
                'id' => 449,
                'discipline_id' => 77,
                'discipline_similaire_id' => 79,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            448 =>
            array(
                'id' => 450,
                'discipline_id' => 78,
                'discipline_similaire_id' => 36,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            449 =>
            array(
                'id' => 451,
                'discipline_id' => 78,
                'discipline_similaire_id' => 47,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            450 =>
            array(
                'id' => 452,
                'discipline_id' => 78,
                'discipline_similaire_id' => 59,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            451 =>
            array(
                'id' => 453,
                'discipline_id' => 78,
                'discipline_similaire_id' => 63,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            452 =>
            array(
                'id' => 454,
                'discipline_id' => 78,
                'discipline_similaire_id' => 64,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            453 =>
            array(
                'id' => 455,
                'discipline_id' => 78,
                'discipline_similaire_id' => 74,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            454 =>
            array(
                'id' => 456,
                'discipline_id' => 79,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            455 =>
            array(
                'id' => 457,
                'discipline_id' => 79,
                'discipline_similaire_id' => 49,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            456 =>
            array(
                'id' => 458,
                'discipline_id' => 79,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            457 =>
            array(
                'id' => 459,
                'discipline_id' => 79,
                'discipline_similaire_id' => 58,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            458 =>
            array(
                'id' => 460,
                'discipline_id' => 79,
                'discipline_similaire_id' => 76,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            459 =>
            array(
                'id' => 461,
                'discipline_id' => 79,
                'discipline_similaire_id' => 77,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            460 =>
            array(
                'id' => 462,
                'discipline_id' => 80,
                'discipline_similaire_id' => 84,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            461 =>
            array(
                'id' => 463,
                'discipline_id' => 80,
                'discipline_similaire_id' => 157,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            462 =>
            array(
                'id' => 464,
                'discipline_id' => 80,
                'discipline_similaire_id' => 85,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            463 =>
            array(
                'id' => 465,
                'discipline_id' => 80,
                'discipline_similaire_id' => 87,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            464 =>
            array(
                'id' => 466,
                'discipline_id' => 80,
                'discipline_similaire_id' => 89,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            465 =>
            array(
                'id' => 467,
                'discipline_id' => 80,
                'discipline_similaire_id' => 90,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            466 =>
            array(
                'id' => 468,
                'discipline_id' => 80,
                'discipline_similaire_id' => 158,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            467 =>
            array(
                'id' => 479,
                'discipline_id' => 82,
                'discipline_similaire_id' => 80,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            468 =>
            array(
                'id' => 480,
                'discipline_id' => 82,
                'discipline_similaire_id' => 85,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            469 =>
            array(
                'id' => 481,
                'discipline_id' => 82,
                'discipline_similaire_id' => 271,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            470 =>
            array(
                'id' => 482,
                'discipline_id' => 82,
                'discipline_similaire_id' => 90,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            471 =>
            array(
                'id' => 483,
                'discipline_id' => 83,
                'discipline_similaire_id' => 81,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            472 =>
            array(
                'id' => 484,
                'discipline_id' => 83,
                'discipline_similaire_id' => 159,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            473 =>
            array(
                'id' => 485,
                'discipline_id' => 83,
                'discipline_similaire_id' => 99,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            474 =>
            array(
                'id' => 486,
                'discipline_id' => 83,
                'discipline_similaire_id' => 88,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            475 =>
            array(
                'id' => 487,
                'discipline_id' => 83,
                'discipline_similaire_id' => 94,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            476 =>
            array(
                'id' => 488,
                'discipline_id' => 83,
                'discipline_similaire_id' => 304,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            477 =>
            array(
                'id' => 491,
                'discipline_id' => 84,
                'discipline_similaire_id' => 80,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            478 =>
            array(
                'id' => 492,
                'discipline_id' => 84,
                'discipline_similaire_id' => 143,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            479 =>
            array(
                'id' => 493,
                'discipline_id' => 84,
                'discipline_similaire_id' => 157,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            480 =>
            array(
                'id' => 494,
                'discipline_id' => 84,
                'discipline_similaire_id' => 85,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            481 =>
            array(
                'id' => 495,
                'discipline_id' => 84,
                'discipline_similaire_id' => 90,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            482 =>
            array(
                'id' => 496,
                'discipline_id' => 84,
                'discipline_similaire_id' => 93,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            483 =>
            array(
                'id' => 497,
                'discipline_id' => 84,
                'discipline_similaire_id' => 177,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            484 =>
            array(
                'id' => 498,
                'discipline_id' => 85,
                'discipline_similaire_id' => 80,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            485 =>
            array(
                'id' => 499,
                'discipline_id' => 85,
                'discipline_similaire_id' => 14,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            486 =>
            array(
                'id' => 500,
                'discipline_id' => 85,
                'discipline_similaire_id' => 86,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            487 =>
            array(
                'id' => 501,
                'discipline_id' => 85,
                'discipline_similaire_id' => 87,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            488 =>
            array(
                'id' => 502,
                'discipline_id' => 85,
                'discipline_similaire_id' => 89,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            489 =>
            array(
                'id' => 503,
                'discipline_id' => 86,
                'discipline_similaire_id' => 180,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            490 =>
            array(
                'id' => 504,
                'discipline_id' => 86,
                'discipline_similaire_id' => 187,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            491 =>
            array(
                'id' => 505,
                'discipline_id' => 86,
                'discipline_similaire_id' => 85,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            492 =>
            array(
                'id' => 506,
                'discipline_id' => 86,
                'discipline_similaire_id' => 87,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            493 =>
            array(
                'id' => 507,
                'discipline_id' => 86,
                'discipline_similaire_id' => 89,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            494 =>
            array(
                'id' => 508,
                'discipline_id' => 87,
                'discipline_similaire_id' => 80,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            495 =>
            array(
                'id' => 509,
                'discipline_id' => 87,
                'discipline_similaire_id' => 84,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            496 =>
            array(
                'id' => 510,
                'discipline_id' => 87,
                'discipline_similaire_id' => 85,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            497 =>
            array(
                'id' => 511,
                'discipline_id' => 87,
                'discipline_similaire_id' => 86,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            498 =>
            array(
                'id' => 512,
                'discipline_id' => 87,
                'discipline_similaire_id' => 149,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            499 =>
            array(
                'id' => 513,
                'discipline_id' => 87,
                'discipline_similaire_id' => 89,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
        ));
        DB::table('liens_disciplines_similaires')->insert(array(
            0 =>
            array(
                'id' => 514,
                'discipline_id' => 87,
                'discipline_similaire_id' => 90,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            1 =>
            array(
                'id' => 515,
                'discipline_id' => 87,
                'discipline_similaire_id' => 158,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            2 =>
            array(
                'id' => 517,
                'discipline_id' => 88,
                'discipline_similaire_id' => 81,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            3 =>
            array(
                'id' => 518,
                'discipline_id' => 88,
                'discipline_similaire_id' => 129,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            4 =>
            array(
                'id' => 519,
                'discipline_id' => 88,
                'discipline_similaire_id' => 134,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            5 =>
            array(
                'id' => 520,
                'discipline_id' => 88,
                'discipline_similaire_id' => 135,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            6 =>
            array(
                'id' => 521,
                'discipline_id' => 88,
                'discipline_similaire_id' => 136,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            7 =>
            array(
                'id' => 522,
                'discipline_id' => 88,
                'discipline_similaire_id' => 170,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            8 =>
            array(
                'id' => 523,
                'discipline_id' => 88,
                'discipline_similaire_id' => 83,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            9 =>
            array(
                'id' => 524,
                'discipline_id' => 88,
                'discipline_similaire_id' => 143,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            10 =>
            array(
                'id' => 525,
                'discipline_id' => 88,
                'discipline_similaire_id' => 176,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            11 =>
            array(
                'id' => 526,
                'discipline_id' => 88,
                'discipline_similaire_id' => 98,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            12 =>
            array(
                'id' => 527,
                'discipline_id' => 88,
                'discipline_similaire_id' => 91,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            13 =>
            array(
                'id' => 528,
                'discipline_id' => 88,
                'discipline_similaire_id' => 177,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            14 =>
            array(
                'id' => 529,
                'discipline_id' => 88,
                'discipline_similaire_id' => 165,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            15 =>
            array(
                'id' => 530,
                'discipline_id' => 88,
                'discipline_similaire_id' => 151,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            16 =>
            array(
                'id' => 531,
                'discipline_id' => 88,
                'discipline_similaire_id' => 304,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            17 =>
            array(
                'id' => 532,
                'discipline_id' => 89,
                'discipline_similaire_id' => 133,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            18 =>
            array(
                'id' => 533,
                'discipline_id' => 89,
                'discipline_similaire_id' => 85,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            19 =>
            array(
                'id' => 534,
                'discipline_id' => 89,
                'discipline_similaire_id' => 87,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            20 =>
            array(
                'id' => 535,
                'discipline_id' => 89,
                'discipline_similaire_id' => 149,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            21 =>
            array(
                'id' => 536,
                'discipline_id' => 89,
                'discipline_similaire_id' => 158,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            22 =>
            array(
                'id' => 537,
                'discipline_id' => 90,
                'discipline_similaire_id' => 81,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            23 =>
            array(
                'id' => 538,
                'discipline_id' => 90,
                'discipline_similaire_id' => 84,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            24 =>
            array(
                'id' => 539,
                'discipline_id' => 90,
                'discipline_similaire_id' => 157,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            25 =>
            array(
                'id' => 540,
                'discipline_id' => 90,
                'discipline_similaire_id' => 85,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            26 =>
            array(
                'id' => 541,
                'discipline_id' => 90,
                'discipline_similaire_id' => 149,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            27 =>
            array(
                'id' => 542,
                'discipline_id' => 90,
                'discipline_similaire_id' => 150,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            28 =>
            array(
                'id' => 543,
                'discipline_id' => 90,
                'discipline_similaire_id' => 91,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            29 =>
            array(
                'id' => 544,
                'discipline_id' => 90,
                'discipline_similaire_id' => 158,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            30 =>
            array(
                'id' => 545,
                'discipline_id' => 90,
                'discipline_similaire_id' => 93,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            31 =>
            array(
                'id' => 546,
                'discipline_id' => 90,
                'discipline_similaire_id' => 151,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            32 =>
            array(
                'id' => 547,
                'discipline_id' => 91,
                'discipline_similaire_id' => 81,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            33 =>
            array(
                'id' => 548,
                'discipline_id' => 91,
                'discipline_similaire_id' => 129,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            34 =>
            array(
                'id' => 549,
                'discipline_id' => 91,
                'discipline_similaire_id' => 143,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            35 =>
            array(
                'id' => 550,
                'discipline_id' => 91,
                'discipline_similaire_id' => 85,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            36 =>
            array(
                'id' => 551,
                'discipline_id' => 91,
                'discipline_similaire_id' => 88,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            37 =>
            array(
                'id' => 552,
                'discipline_id' => 91,
                'discipline_similaire_id' => 98,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            38 =>
            array(
                'id' => 553,
                'discipline_id' => 91,
                'discipline_similaire_id' => 90,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            39 =>
            array(
                'id' => 554,
                'discipline_id' => 91,
                'discipline_similaire_id' => 177,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            40 =>
            array(
                'id' => 555,
                'discipline_id' => 92,
                'discipline_similaire_id' => 80,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            41 =>
            array(
                'id' => 556,
                'discipline_id' => 92,
                'discipline_similaire_id' => 141,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            42 =>
            array(
                'id' => 557,
                'discipline_id' => 92,
                'discipline_similaire_id' => 84,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            43 =>
            array(
                'id' => 558,
                'discipline_id' => 92,
                'discipline_similaire_id' => 155,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            44 =>
            array(
                'id' => 559,
                'discipline_id' => 92,
                'discipline_similaire_id' => 157,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            45 =>
            array(
                'id' => 560,
                'discipline_id' => 92,
                'discipline_similaire_id' => 87,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            46 =>
            array(
                'id' => 561,
                'discipline_id' => 92,
                'discipline_similaire_id' => 162,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            47 =>
            array(
                'id' => 562,
                'discipline_id' => 92,
                'discipline_similaire_id' => 156,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            48 =>
            array(
                'id' => 563,
                'discipline_id' => 92,
                'discipline_similaire_id' => 89,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            49 =>
            array(
                'id' => 564,
                'discipline_id' => 92,
                'discipline_similaire_id' => 116,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            50 =>
            array(
                'id' => 565,
                'discipline_id' => 92,
                'discipline_similaire_id' => 93,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            51 =>
            array(
                'id' => 566,
                'discipline_id' => 93,
                'discipline_similaire_id' => 157,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            52 =>
            array(
                'id' => 567,
                'discipline_id' => 93,
                'discipline_similaire_id' => 149,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            53 =>
            array(
                'id' => 568,
                'discipline_id' => 93,
                'discipline_similaire_id' => 150,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            54 =>
            array(
                'id' => 569,
                'discipline_id' => 93,
                'discipline_similaire_id' => 90,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            55 =>
            array(
                'id' => 570,
                'discipline_id' => 93,
                'discipline_similaire_id' => 151,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            56 =>
            array(
                'id' => 571,
                'discipline_id' => 94,
                'discipline_similaire_id' => 81,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            57 =>
            array(
                'id' => 572,
                'discipline_id' => 94,
                'discipline_similaire_id' => 159,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            58 =>
            array(
                'id' => 573,
                'discipline_id' => 94,
                'discipline_similaire_id' => 99,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            59 =>
            array(
                'id' => 574,
                'discipline_id' => 94,
                'discipline_similaire_id' => 83,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            60 =>
            array(
                'id' => 575,
                'discipline_id' => 94,
                'discipline_similaire_id' => 88,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            61 =>
            array(
                'id' => 576,
                'discipline_id' => 94,
                'discipline_similaire_id' => 304,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            62 =>
            array(
                'id' => 577,
                'discipline_id' => 95,
                'discipline_similaire_id' => 141,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            63 =>
            array(
                'id' => 578,
                'discipline_id' => 95,
                'discipline_similaire_id' => 84,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            64 =>
            array(
                'id' => 579,
                'discipline_id' => 95,
                'discipline_similaire_id' => 88,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            65 =>
            array(
                'id' => 580,
                'discipline_id' => 95,
                'discipline_similaire_id' => 98,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            66 =>
            array(
                'id' => 581,
                'discipline_id' => 95,
                'discipline_similaire_id' => 90,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            67 =>
            array(
                'id' => 582,
                'discipline_id' => 95,
                'discipline_similaire_id' => 91,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            68 =>
            array(
                'id' => 583,
                'discipline_id' => 95,
                'discipline_similaire_id' => 177,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            69 =>
            array(
                'id' => 584,
                'discipline_id' => 96,
                'discipline_similaire_id' => 81,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            70 =>
            array(
                'id' => 585,
                'discipline_id' => 96,
                'discipline_similaire_id' => 170,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            71 =>
            array(
                'id' => 586,
                'discipline_id' => 96,
                'discipline_similaire_id' => 143,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            72 =>
            array(
                'id' => 587,
                'discipline_id' => 96,
                'discipline_similaire_id' => 88,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            73 =>
            array(
                'id' => 588,
                'discipline_id' => 96,
                'discipline_similaire_id' => 176,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            74 =>
            array(
                'id' => 589,
                'discipline_id' => 96,
                'discipline_similaire_id' => 91,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            75 =>
            array(
                'id' => 590,
                'discipline_id' => 97,
                'discipline_similaire_id' => 147,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            76 =>
            array(
                'id' => 591,
                'discipline_id' => 97,
                'discipline_similaire_id' => 88,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            77 =>
            array(
                'id' => 592,
                'discipline_id' => 97,
                'discipline_similaire_id' => 150,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            78 =>
            array(
                'id' => 593,
                'discipline_id' => 97,
                'discipline_similaire_id' => 90,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            79 =>
            array(
                'id' => 594,
                'discipline_id' => 97,
                'discipline_similaire_id' => 93,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            80 =>
            array(
                'id' => 595,
                'discipline_id' => 97,
                'discipline_similaire_id' => 151,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            81 =>
            array(
                'id' => 596,
                'discipline_id' => 98,
                'discipline_similaire_id' => 81,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            82 =>
            array(
                'id' => 597,
                'discipline_id' => 98,
                'discipline_similaire_id' => 83,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            83 =>
            array(
                'id' => 598,
                'discipline_id' => 98,
                'discipline_similaire_id' => 143,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            84 =>
            array(
                'id' => 599,
                'discipline_id' => 98,
                'discipline_similaire_id' => 88,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            85 =>
            array(
                'id' => 600,
                'discipline_id' => 98,
                'discipline_similaire_id' => 176,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            86 =>
            array(
                'id' => 601,
                'discipline_id' => 98,
                'discipline_similaire_id' => 90,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            87 =>
            array(
                'id' => 602,
                'discipline_id' => 98,
                'discipline_similaire_id' => 91,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            88 =>
            array(
                'id' => 603,
                'discipline_id' => 98,
                'discipline_similaire_id' => 177,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            89 =>
            array(
                'id' => 604,
                'discipline_id' => 99,
                'discipline_similaire_id' => 81,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            90 =>
            array(
                'id' => 605,
                'discipline_id' => 99,
                'discipline_similaire_id' => 104,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            91 =>
            array(
                'id' => 606,
                'discipline_id' => 99,
                'discipline_similaire_id' => 159,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            92 =>
            array(
                'id' => 607,
                'discipline_id' => 99,
                'discipline_similaire_id' => 83,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            93 =>
            array(
                'id' => 608,
                'discipline_id' => 99,
                'discipline_similaire_id' => 286,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            94 =>
            array(
                'id' => 609,
                'discipline_id' => 99,
                'discipline_similaire_id' => 162,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            95 =>
            array(
                'id' => 610,
                'discipline_id' => 99,
                'discipline_similaire_id' => 88,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            96 =>
            array(
                'id' => 611,
                'discipline_id' => 99,
                'discipline_similaire_id' => 163,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            97 =>
            array(
                'id' => 612,
                'discipline_id' => 99,
                'discipline_similaire_id' => 100,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            98 =>
            array(
                'id' => 613,
                'discipline_id' => 99,
                'discipline_similaire_id' => 94,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            99 =>
            array(
                'id' => 614,
                'discipline_id' => 99,
                'discipline_similaire_id' => 304,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            100 =>
            array(
                'id' => 615,
                'discipline_id' => 100,
                'discipline_similaire_id' => 81,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            101 =>
            array(
                'id' => 616,
                'discipline_id' => 100,
                'discipline_similaire_id' => 83,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            102 =>
            array(
                'id' => 617,
                'discipline_id' => 100,
                'discipline_similaire_id' => 98,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            103 =>
            array(
                'id' => 618,
                'discipline_id' => 100,
                'discipline_similaire_id' => 94,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            104 =>
            array(
                'id' => 619,
                'discipline_id' => 102,
                'discipline_similaire_id' => 125,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            105 =>
            array(
                'id' => 620,
                'discipline_id' => 102,
                'discipline_similaire_id' => 104,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            106 =>
            array(
                'id' => 621,
                'discipline_id' => 102,
                'discipline_similaire_id' => 124,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            107 =>
            array(
                'id' => 622,
                'discipline_id' => 102,
                'discipline_similaire_id' => 121,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            108 =>
            array(
                'id' => 623,
                'discipline_id' => 102,
                'discipline_similaire_id' => 278,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            109 =>
            array(
                'id' => 624,
                'discipline_id' => 102,
                'discipline_similaire_id' => 162,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            110 =>
            array(
                'id' => 625,
                'discipline_id' => 103,
                'discipline_similaire_id' => 15,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            111 =>
            array(
                'id' => 626,
                'discipline_id' => 103,
                'discipline_similaire_id' => 173,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            112 =>
            array(
                'id' => 627,
                'discipline_id' => 103,
                'discipline_similaire_id' => 175,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            113 =>
            array(
                'id' => 628,
                'discipline_id' => 104,
                'discipline_similaire_id' => 305,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            114 =>
            array(
                'id' => 629,
                'discipline_id' => 104,
                'discipline_similaire_id' => 102,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            115 =>
            array(
                'id' => 630,
                'discipline_id' => 104,
                'discipline_similaire_id' => 159,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            116 =>
            array(
                'id' => 631,
                'discipline_id' => 104,
                'discipline_similaire_id' => 99,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            117 =>
            array(
                'id' => 632,
                'discipline_id' => 104,
                'discipline_similaire_id' => 286,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            118 =>
            array(
                'id' => 633,
                'discipline_id' => 104,
                'discipline_similaire_id' => 162,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            119 =>
            array(
                'id' => 634,
                'discipline_id' => 105,
                'discipline_similaire_id' => 81,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            120 =>
            array(
                'id' => 635,
                'discipline_id' => 105,
                'discipline_similaire_id' => 104,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            121 =>
            array(
                'id' => 636,
                'discipline_id' => 105,
                'discipline_similaire_id' => 99,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            122 =>
            array(
                'id' => 637,
                'discipline_id' => 105,
                'discipline_similaire_id' => 83,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            123 =>
            array(
                'id' => 638,
                'discipline_id' => 105,
                'discipline_similaire_id' => 286,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            124 =>
            array(
                'id' => 639,
                'discipline_id' => 105,
                'discipline_similaire_id' => 162,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            125 =>
            array(
                'id' => 640,
                'discipline_id' => 105,
                'discipline_similaire_id' => 94,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            126 =>
            array(
                'id' => 641,
                'discipline_id' => 105,
                'discipline_similaire_id' => 304,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            127 =>
            array(
                'id' => 642,
                'discipline_id' => 106,
                'discipline_similaire_id' => 104,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            128 =>
            array(
                'id' => 643,
                'discipline_id' => 106,
                'discipline_similaire_id' => 112,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            129 =>
            array(
                'id' => 644,
                'discipline_id' => 106,
                'discipline_similaire_id' => 175,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            130 =>
            array(
                'id' => 645,
                'discipline_id' => 106,
                'discipline_similaire_id' => 116,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            131 =>
            array(
                'id' => 646,
                'discipline_id' => 106,
                'discipline_similaire_id' => 117,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            132 =>
            array(
                'id' => 647,
                'discipline_id' => 106,
                'discipline_similaire_id' => 118,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            133 =>
            array(
                'id' => 648,
                'discipline_id' => 106,
                'discipline_similaire_id' => 287,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            134 =>
            array(
                'id' => 649,
                'discipline_id' => 107,
                'discipline_similaire_id' => 104,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            135 =>
            array(
                'id' => 650,
                'discipline_id' => 107,
                'discipline_similaire_id' => 139,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            136 =>
            array(
                'id' => 651,
                'discipline_id' => 107,
                'discipline_similaire_id' => 175,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            137 =>
            array(
                'id' => 652,
                'discipline_id' => 107,
                'discipline_similaire_id' => 162,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            138 =>
            array(
                'id' => 653,
                'discipline_id' => 107,
                'discipline_similaire_id' => 116,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            139 =>
            array(
                'id' => 654,
                'discipline_id' => 107,
                'discipline_similaire_id' => 117,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            140 =>
            array(
                'id' => 655,
                'discipline_id' => 107,
                'discipline_similaire_id' => 287,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            141 =>
            array(
                'id' => 656,
                'discipline_id' => 107,
                'discipline_similaire_id' => 119,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            142 =>
            array(
                'id' => 657,
                'discipline_id' => 108,
                'discipline_similaire_id' => 103,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            143 =>
            array(
                'id' => 658,
                'discipline_id' => 108,
                'discipline_similaire_id' => 281,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            144 =>
            array(
                'id' => 659,
                'discipline_id' => 108,
                'discipline_similaire_id' => 284,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            145 =>
            array(
                'id' => 660,
                'discipline_id' => 108,
                'discipline_similaire_id' => 282,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            146 =>
            array(
                'id' => 661,
                'discipline_id' => 108,
                'discipline_similaire_id' => 283,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            147 =>
            array(
                'id' => 662,
                'discipline_id' => 108,
                'discipline_similaire_id' => 125,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            148 =>
            array(
                'id' => 663,
                'discipline_id' => 108,
                'discipline_similaire_id' => 129,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            149 =>
            array(
                'id' => 664,
                'discipline_id' => 108,
                'discipline_similaire_id' => 15,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            150 =>
            array(
                'id' => 665,
                'discipline_id' => 108,
                'discipline_similaire_id' => 109,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            151 =>
            array(
                'id' => 666,
                'discipline_id' => 108,
                'discipline_similaire_id' => 175,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            152 =>
            array(
                'id' => 667,
                'discipline_id' => 108,
                'discipline_similaire_id' => 114,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            153 =>
            array(
                'id' => 668,
                'discipline_id' => 108,
                'discipline_similaire_id' => 120,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            154 =>
            array(
                'id' => 669,
                'discipline_id' => 109,
                'discipline_similaire_id' => 122,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            155 =>
            array(
                'id' => 670,
                'discipline_id' => 109,
                'discipline_similaire_id' => 103,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            156 =>
            array(
                'id' => 671,
                'discipline_id' => 109,
                'discipline_similaire_id' => 180,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            157 =>
            array(
                'id' => 672,
                'discipline_id' => 109,
                'discipline_similaire_id' => 126,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            158 =>
            array(
                'id' => 673,
                'discipline_id' => 109,
                'discipline_similaire_id' => 127,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            159 =>
            array(
                'id' => 674,
                'discipline_id' => 109,
                'discipline_similaire_id' => 108,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            160 =>
            array(
                'id' => 675,
                'discipline_id' => 109,
                'discipline_similaire_id' => 86,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            161 =>
            array(
                'id' => 676,
                'discipline_id' => 109,
                'discipline_similaire_id' => 175,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            162 =>
            array(
                'id' => 677,
                'discipline_id' => 110,
                'discipline_similaire_id' => 102,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            163 =>
            array(
                'id' => 678,
                'discipline_id' => 110,
                'discipline_similaire_id' => 269,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            164 =>
            array(
                'id' => 679,
                'discipline_id' => 110,
                'discipline_similaire_id' => 173,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            165 =>
            array(
                'id' => 680,
                'discipline_id' => 110,
                'discipline_similaire_id' => 238,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            166 =>
            array(
                'id' => 681,
                'discipline_id' => 110,
                'discipline_similaire_id' => 227,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            167 =>
            array(
                'id' => 682,
                'discipline_id' => 110,
                'discipline_similaire_id' => 175,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            168 =>
            array(
                'id' => 683,
                'discipline_id' => 110,
                'discipline_similaire_id' => 273,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            169 =>
            array(
                'id' => 684,
                'discipline_id' => 110,
                'discipline_similaire_id' => 275,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            170 =>
            array(
                'id' => 685,
                'discipline_id' => 110,
                'discipline_similaire_id' => 118,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            171 =>
            array(
                'id' => 686,
                'discipline_id' => 111,
                'discipline_similaire_id' => 103,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            172 =>
            array(
                'id' => 687,
                'discipline_id' => 111,
                'discipline_similaire_id' => 172,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            173 =>
            array(
                'id' => 688,
                'discipline_id' => 111,
                'discipline_similaire_id' => 175,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            174 =>
            array(
                'id' => 689,
                'discipline_id' => 111,
                'discipline_similaire_id' => 306,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            175 =>
            array(
                'id' => 690,
                'discipline_id' => 103,
                'discipline_similaire_id' => 306,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            176 =>
            array(
                'id' => 691,
                'discipline_id' => 108,
                'discipline_similaire_id' => 306,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            177 =>
            array(
                'id' => 692,
                'discipline_id' => 88,
                'discipline_similaire_id' => 307,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            178 =>
            array(
                'id' => 694,
                'discipline_id' => 91,
                'discipline_similaire_id' => 307,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            179 =>
            array(
                'id' => 695,
                'discipline_id' => 98,
                'discipline_similaire_id' => 307,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            180 =>
            array(
                'id' => 696,
                'discipline_id' => 112,
                'discipline_similaire_id' => 104,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            181 =>
            array(
                'id' => 697,
                'discipline_id' => 112,
                'discipline_similaire_id' => 286,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            182 =>
            array(
                'id' => 698,
                'discipline_id' => 112,
                'discipline_similaire_id' => 141,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            183 =>
            array(
                'id' => 699,
                'discipline_id' => 112,
                'discipline_similaire_id' => 162,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            184 =>
            array(
                'id' => 700,
                'discipline_id' => 112,
                'discipline_similaire_id' => 116,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            185 =>
            array(
                'id' => 701,
                'discipline_id' => 112,
                'discipline_similaire_id' => 117,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            186 =>
            array(
                'id' => 702,
                'discipline_id' => 112,
                'discipline_similaire_id' => 118,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            187 =>
            array(
                'id' => 703,
                'discipline_id' => 112,
                'discipline_similaire_id' => 119,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            188 =>
            array(
                'id' => 704,
                'discipline_id' => 112,
                'discipline_similaire_id' => 238,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            189 =>
            array(
                'id' => 705,
                'discipline_id' => 113,
                'discipline_similaire_id' => 103,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            190 =>
            array(
                'id' => 706,
                'discipline_id' => 113,
                'discipline_similaire_id' => 108,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            191 =>
            array(
                'id' => 707,
                'discipline_id' => 113,
                'discipline_similaire_id' => 173,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            192 =>
            array(
                'id' => 708,
                'discipline_id' => 113,
                'discipline_similaire_id' => 306,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            193 =>
            array(
                'id' => 709,
                'discipline_id' => 113,
                'discipline_similaire_id' => 118,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            194 =>
            array(
                'id' => 710,
                'discipline_id' => 114,
                'discipline_similaire_id' => 103,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            195 =>
            array(
                'id' => 711,
                'discipline_id' => 114,
                'discipline_similaire_id' => 108,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            196 =>
            array(
                'id' => 712,
                'discipline_id' => 114,
                'discipline_similaire_id' => 175,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            197 =>
            array(
                'id' => 713,
                'discipline_id' => 114,
                'discipline_similaire_id' => 306,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            198 =>
            array(
                'id' => 714,
                'discipline_id' => 115,
                'discipline_similaire_id' => 104,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            199 =>
            array(
                'id' => 715,
                'discipline_id' => 115,
                'discipline_similaire_id' => 159,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            200 =>
            array(
                'id' => 716,
                'discipline_id' => 115,
                'discipline_similaire_id' => 99,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            201 =>
            array(
                'id' => 717,
                'discipline_id' => 115,
                'discipline_similaire_id' => 286,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            202 =>
            array(
                'id' => 718,
                'discipline_id' => 115,
                'discipline_similaire_id' => 141,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            203 =>
            array(
                'id' => 719,
                'discipline_id' => 115,
                'discipline_similaire_id' => 116,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            204 =>
            array(
                'id' => 720,
                'discipline_id' => 115,
                'discipline_similaire_id' => 118,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            205 =>
            array(
                'id' => 721,
                'discipline_id' => 115,
                'discipline_similaire_id' => 287,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            206 =>
            array(
                'id' => 722,
                'discipline_id' => 115,
                'discipline_similaire_id' => 119,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            207 =>
            array(
                'id' => 723,
                'discipline_id' => 116,
                'discipline_similaire_id' => 139,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            208 =>
            array(
                'id' => 724,
                'discipline_id' => 116,
                'discipline_similaire_id' => 141,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            209 =>
            array(
                'id' => 725,
                'discipline_id' => 116,
                'discipline_similaire_id' => 112,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            210 =>
            array(
                'id' => 726,
                'discipline_id' => 116,
                'discipline_similaire_id' => 162,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            211 =>
            array(
                'id' => 727,
                'discipline_id' => 116,
                'discipline_similaire_id' => 117,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            212 =>
            array(
                'id' => 728,
                'discipline_id' => 116,
                'discipline_similaire_id' => 118,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            213 =>
            array(
                'id' => 729,
                'discipline_id' => 116,
                'discipline_similaire_id' => 287,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            214 =>
            array(
                'id' => 730,
                'discipline_id' => 116,
                'discipline_similaire_id' => 119,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            215 =>
            array(
                'id' => 731,
                'discipline_id' => 117,
                'discipline_similaire_id' => 139,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            216 =>
            array(
                'id' => 732,
                'discipline_id' => 117,
                'discipline_similaire_id' => 141,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            217 =>
            array(
                'id' => 733,
                'discipline_id' => 117,
                'discipline_similaire_id' => 112,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            218 =>
            array(
                'id' => 734,
                'discipline_id' => 117,
                'discipline_similaire_id' => 175,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            219 =>
            array(
                'id' => 735,
                'discipline_id' => 117,
                'discipline_similaire_id' => 116,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            220 =>
            array(
                'id' => 736,
                'discipline_id' => 117,
                'discipline_similaire_id' => 287,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            221 =>
            array(
                'id' => 737,
                'discipline_id' => 117,
                'discipline_similaire_id' => 119,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            222 =>
            array(
                'id' => 738,
                'discipline_id' => 118,
                'discipline_similaire_id' => 102,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            223 =>
            array(
                'id' => 739,
                'discipline_id' => 118,
                'discipline_similaire_id' => 104,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            224 =>
            array(
                'id' => 740,
                'discipline_id' => 118,
                'discipline_similaire_id' => 139,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            225 =>
            array(
                'id' => 741,
                'discipline_id' => 118,
                'discipline_similaire_id' => 141,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            226 =>
            array(
                'id' => 742,
                'discipline_id' => 118,
                'discipline_similaire_id' => 112,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            227 =>
            array(
                'id' => 743,
                'discipline_id' => 118,
                'discipline_similaire_id' => 175,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            228 =>
            array(
                'id' => 744,
                'discipline_id' => 118,
                'discipline_similaire_id' => 116,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            229 =>
            array(
                'id' => 745,
                'discipline_id' => 118,
                'discipline_similaire_id' => 117,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            230 =>
            array(
                'id' => 746,
                'discipline_id' => 118,
                'discipline_similaire_id' => 287,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            231 =>
            array(
                'id' => 747,
                'discipline_id' => 118,
                'discipline_similaire_id' => 119,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            232 =>
            array(
                'id' => 748,
                'discipline_id' => 108,
                'discipline_similaire_id' => 303,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            233 =>
            array(
                'id' => 749,
                'discipline_id' => 119,
                'discipline_similaire_id' => 104,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            234 =>
            array(
                'id' => 750,
                'discipline_id' => 119,
                'discipline_similaire_id' => 139,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            235 =>
            array(
                'id' => 751,
                'discipline_id' => 119,
                'discipline_similaire_id' => 141,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            236 =>
            array(
                'id' => 752,
                'discipline_id' => 119,
                'discipline_similaire_id' => 116,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            237 =>
            array(
                'id' => 753,
                'discipline_id' => 119,
                'discipline_similaire_id' => 117,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            238 =>
            array(
                'id' => 754,
                'discipline_id' => 119,
                'discipline_similaire_id' => 118,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            239 =>
            array(
                'id' => 755,
                'discipline_id' => 119,
                'discipline_similaire_id' => 287,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            240 =>
            array(
                'id' => 756,
                'discipline_id' => 121,
                'discipline_similaire_id' => 122,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            241 =>
            array(
                'id' => 757,
                'discipline_id' => 121,
                'discipline_similaire_id' => 281,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            242 =>
            array(
                'id' => 758,
                'discipline_id' => 121,
                'discipline_similaire_id' => 284,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            243 =>
            array(
                'id' => 759,
                'discipline_id' => 121,
                'discipline_similaire_id' => 282,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            244 =>
            array(
                'id' => 760,
                'discipline_id' => 121,
                'discipline_similaire_id' => 123,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            245 =>
            array(
                'id' => 761,
                'discipline_id' => 121,
                'discipline_similaire_id' => 283,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            246 =>
            array(
                'id' => 762,
                'discipline_id' => 121,
                'discipline_similaire_id' => 124,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            247 =>
            array(
                'id' => 763,
                'discipline_id' => 121,
                'discipline_similaire_id' => 129,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            248 =>
            array(
                'id' => 764,
                'discipline_id' => 121,
                'discipline_similaire_id' => 126,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            249 =>
            array(
                'id' => 765,
                'discipline_id' => 121,
                'discipline_similaire_id' => 278,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            250 =>
            array(
                'id' => 766,
                'discipline_id' => 121,
                'discipline_similaire_id' => 297,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            251 =>
            array(
                'id' => 767,
                'discipline_id' => 121,
                'discipline_similaire_id' => 277,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            252 =>
            array(
                'id' => 768,
                'discipline_id' => 122,
                'discipline_similaire_id' => 124,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            253 =>
            array(
                'id' => 769,
                'discipline_id' => 122,
                'discipline_similaire_id' => 180,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            254 =>
            array(
                'id' => 770,
                'discipline_id' => 122,
                'discipline_similaire_id' => 121,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            255 =>
            array(
                'id' => 771,
                'discipline_id' => 122,
                'discipline_similaire_id' => 126,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            256 =>
            array(
                'id' => 772,
                'discipline_id' => 122,
                'discipline_similaire_id' => 127,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            257 =>
            array(
                'id' => 773,
                'discipline_id' => 122,
                'discipline_similaire_id' => 187,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            258 =>
            array(
                'id' => 775,
                'discipline_id' => 123,
                'discipline_similaire_id' => 281,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            259 =>
            array(
                'id' => 776,
                'discipline_id' => 123,
                'discipline_similaire_id' => 284,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            260 =>
            array(
                'id' => 777,
                'discipline_id' => 123,
                'discipline_similaire_id' => 282,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            261 =>
            array(
                'id' => 778,
                'discipline_id' => 123,
                'discipline_similaire_id' => 283,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            262 =>
            array(
                'id' => 779,
                'discipline_id' => 123,
                'discipline_similaire_id' => 121,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            263 =>
            array(
                'id' => 780,
                'discipline_id' => 123,
                'discipline_similaire_id' => 108,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            264 =>
            array(
                'id' => 781,
                'discipline_id' => 124,
                'discipline_similaire_id' => 122,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            265 =>
            array(
                'id' => 782,
                'discipline_id' => 124,
                'discipline_similaire_id' => 281,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            266 =>
            array(
                'id' => 783,
                'discipline_id' => 124,
                'discipline_similaire_id' => 284,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            267 =>
            array(
                'id' => 784,
                'discipline_id' => 124,
                'discipline_similaire_id' => 282,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            268 =>
            array(
                'id' => 785,
                'discipline_id' => 124,
                'discipline_similaire_id' => 123,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            269 =>
            array(
                'id' => 786,
                'discipline_id' => 124,
                'discipline_similaire_id' => 283,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            270 =>
            array(
                'id' => 787,
                'discipline_id' => 124,
                'discipline_similaire_id' => 129,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            271 =>
            array(
                'id' => 788,
                'discipline_id' => 124,
                'discipline_similaire_id' => 121,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            272 =>
            array(
                'id' => 789,
                'discipline_id' => 124,
                'discipline_similaire_id' => 278,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            273 =>
            array(
                'id' => 790,
                'discipline_id' => 125,
                'discipline_similaire_id' => 102,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            274 =>
            array(
                'id' => 791,
                'discipline_id' => 125,
                'discipline_similaire_id' => 129,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            275 =>
            array(
                'id' => 792,
                'discipline_id' => 125,
                'discipline_similaire_id' => 126,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            276 =>
            array(
                'id' => 793,
                'discipline_id' => 125,
                'discipline_similaire_id' => 61,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            277 =>
            array(
                'id' => 794,
                'discipline_id' => 125,
                'discipline_similaire_id' => 278,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            278 =>
            array(
                'id' => 795,
                'discipline_id' => 125,
                'discipline_similaire_id' => 108,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            279 =>
            array(
                'id' => 796,
                'discipline_id' => 126,
                'discipline_similaire_id' => 122,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            280 =>
            array(
                'id' => 797,
                'discipline_id' => 126,
                'discipline_similaire_id' => 123,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            281 =>
            array(
                'id' => 798,
                'discipline_id' => 126,
                'discipline_similaire_id' => 168,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            282 =>
            array(
                'id' => 799,
                'discipline_id' => 126,
                'discipline_similaire_id' => 127,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            283 =>
            array(
                'id' => 800,
                'discipline_id' => 126,
                'discipline_similaire_id' => 109,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            284 =>
            array(
                'id' => 801,
                'discipline_id' => 126,
                'discipline_similaire_id' => 297,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            285 =>
            array(
                'id' => 802,
                'discipline_id' => 126,
                'discipline_similaire_id' => 277,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            286 =>
            array(
                'id' => 803,
                'discipline_id' => 127,
                'discipline_similaire_id' => 122,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            287 =>
            array(
                'id' => 804,
                'discipline_id' => 127,
                'discipline_similaire_id' => 126,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            288 =>
            array(
                'id' => 805,
                'discipline_id' => 127,
                'discipline_similaire_id' => 109,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            289 =>
            array(
                'id' => 806,
                'discipline_id' => 127,
                'discipline_similaire_id' => 297,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            290 =>
            array(
                'id' => 807,
                'discipline_id' => 128,
                'discipline_similaire_id' => 122,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            291 =>
            array(
                'id' => 808,
                'discipline_id' => 128,
                'discipline_similaire_id' => 121,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            292 =>
            array(
                'id' => 809,
                'discipline_id' => 128,
                'discipline_similaire_id' => 126,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            293 =>
            array(
                'id' => 810,
                'discipline_id' => 128,
                'discipline_similaire_id' => 127,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            294 =>
            array(
                'id' => 811,
                'discipline_id' => 128,
                'discipline_similaire_id' => 244,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            295 =>
            array(
                'id' => 812,
                'discipline_id' => 129,
                'discipline_similaire_id' => 122,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            296 =>
            array(
                'id' => 813,
                'discipline_id' => 129,
                'discipline_similaire_id' => 125,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            297 =>
            array(
                'id' => 814,
                'discipline_id' => 129,
                'discipline_similaire_id' => 102,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            298 =>
            array(
                'id' => 815,
                'discipline_id' => 129,
                'discipline_similaire_id' => 124,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            299 =>
            array(
                'id' => 816,
                'discipline_id' => 129,
                'discipline_similaire_id' => 121,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            300 =>
            array(
                'id' => 817,
                'discipline_id' => 129,
                'discipline_similaire_id' => 88,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            301 =>
            array(
                'id' => 818,
                'discipline_id' => 130,
                'discipline_similaire_id' => 250,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            302 =>
            array(
                'id' => 819,
                'discipline_id' => 130,
                'discipline_similaire_id' => 249,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            303 =>
            array(
                'id' => 820,
                'discipline_id' => 130,
                'discipline_similaire_id' => 251,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            304 =>
            array(
                'id' => 821,
                'discipline_id' => 130,
                'discipline_similaire_id' => 147,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            305 =>
            array(
                'id' => 822,
                'discipline_id' => 130,
                'discipline_similaire_id' => 257,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            306 =>
            array(
                'id' => 823,
                'discipline_id' => 130,
                'discipline_similaire_id' => 146,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            307 =>
            array(
                'id' => 824,
                'discipline_id' => 130,
                'discipline_similaire_id' => 156,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            308 =>
            array(
                'id' => 825,
                'discipline_id' => 130,
                'discipline_similaire_id' => 151,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            309 =>
            array(
                'id' => 826,
                'discipline_id' => 131,
                'discipline_similaire_id' => 166,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            310 =>
            array(
                'id' => 827,
                'discipline_id' => 131,
                'discipline_similaire_id' => 160,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            311 =>
            array(
                'id' => 828,
                'discipline_id' => 131,
                'discipline_similaire_id' => 137,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            312 =>
            array(
                'id' => 829,
                'discipline_id' => 131,
                'discipline_similaire_id' => 234,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            313 =>
            array(
                'id' => 830,
                'discipline_id' => 131,
                'discipline_similaire_id' => 164,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            314 =>
            array(
                'id' => 831,
                'discipline_id' => 131,
                'discipline_similaire_id' => 145,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            315 =>
            array(
                'id' => 832,
                'discipline_id' => 131,
                'discipline_similaire_id' => 161,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            316 =>
            array(
                'id' => 833,
                'discipline_id' => 131,
                'discipline_similaire_id' => 165,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            317 =>
            array(
                'id' => 834,
                'discipline_id' => 132,
                'discipline_similaire_id' => 147,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            318 =>
            array(
                'id' => 835,
                'discipline_id' => 132,
                'discipline_similaire_id' => 149,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            319 =>
            array(
                'id' => 836,
                'discipline_id' => 132,
                'discipline_similaire_id' => 150,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            320 =>
            array(
                'id' => 837,
                'discipline_id' => 132,
                'discipline_similaire_id' => 151,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            321 =>
            array(
                'id' => 838,
                'discipline_id' => 133,
                'discipline_similaire_id' => 153,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            322 =>
            array(
                'id' => 839,
                'discipline_id' => 133,
                'discipline_similaire_id' => 134,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            323 =>
            array(
                'id' => 840,
                'discipline_id' => 133,
                'discipline_similaire_id' => 135,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            324 =>
            array(
                'id' => 841,
                'discipline_id' => 133,
                'discipline_similaire_id' => 136,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            325 =>
            array(
                'id' => 842,
                'discipline_id' => 133,
                'discipline_similaire_id' => 147,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            326 =>
            array(
                'id' => 843,
                'discipline_id' => 133,
                'discipline_similaire_id' => 146,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            327 =>
            array(
                'id' => 844,
                'discipline_id' => 133,
                'discipline_similaire_id' => 88,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            328 =>
            array(
                'id' => 845,
                'discipline_id' => 133,
                'discipline_similaire_id' => 151,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            329 =>
            array(
                'id' => 846,
                'discipline_id' => 134,
                'discipline_similaire_id' => 153,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            330 =>
            array(
                'id' => 847,
                'discipline_id' => 134,
                'discipline_similaire_id' => 133,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            331 =>
            array(
                'id' => 848,
                'discipline_id' => 134,
                'discipline_similaire_id' => 135,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            332 =>
            array(
                'id' => 849,
                'discipline_id' => 134,
                'discipline_similaire_id' => 136,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            333 =>
            array(
                'id' => 850,
                'discipline_id' => 134,
                'discipline_similaire_id' => 88,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            334 =>
            array(
                'id' => 851,
                'discipline_id' => 134,
                'discipline_similaire_id' => 151,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            335 =>
            array(
                'id' => 852,
                'discipline_id' => 135,
                'discipline_similaire_id' => 153,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            336 =>
            array(
                'id' => 853,
                'discipline_id' => 135,
                'discipline_similaire_id' => 133,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            337 =>
            array(
                'id' => 854,
                'discipline_id' => 135,
                'discipline_similaire_id' => 134,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            338 =>
            array(
                'id' => 855,
                'discipline_id' => 135,
                'discipline_similaire_id' => 136,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            339 =>
            array(
                'id' => 856,
                'discipline_id' => 135,
                'discipline_similaire_id' => 147,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            340 =>
            array(
                'id' => 857,
                'discipline_id' => 135,
                'discipline_similaire_id' => 151,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            341 =>
            array(
                'id' => 858,
                'discipline_id' => 136,
                'discipline_similaire_id' => 133,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            342 =>
            array(
                'id' => 859,
                'discipline_id' => 136,
                'discipline_similaire_id' => 134,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            343 =>
            array(
                'id' => 860,
                'discipline_id' => 136,
                'discipline_similaire_id' => 135,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            344 =>
            array(
                'id' => 861,
                'discipline_id' => 136,
                'discipline_similaire_id' => 146,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            345 =>
            array(
                'id' => 862,
                'discipline_id' => 136,
                'discipline_similaire_id' => 88,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            346 =>
            array(
                'id' => 863,
                'discipline_id' => 136,
                'discipline_similaire_id' => 149,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            347 =>
            array(
                'id' => 864,
                'discipline_id' => 136,
                'discipline_similaire_id' => 151,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            348 =>
            array(
                'id' => 865,
                'discipline_id' => 137,
                'discipline_similaire_id' => 131,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            349 =>
            array(
                'id' => 866,
                'discipline_id' => 137,
                'discipline_similaire_id' => 166,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            350 =>
            array(
                'id' => 867,
                'discipline_id' => 137,
                'discipline_similaire_id' => 164,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            351 =>
            array(
                'id' => 868,
                'discipline_id' => 137,
                'discipline_similaire_id' => 165,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            352 =>
            array(
                'id' => 869,
                'discipline_id' => 138,
                'discipline_similaire_id' => 131,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            353 =>
            array(
                'id' => 870,
                'discipline_id' => 138,
                'discipline_similaire_id' => 137,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            354 =>
            array(
                'id' => 871,
                'discipline_id' => 138,
                'discipline_similaire_id' => 164,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            355 =>
            array(
                'id' => 872,
                'discipline_id' => 138,
                'discipline_similaire_id' => 145,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            356 =>
            array(
                'id' => 873,
                'discipline_id' => 138,
                'discipline_similaire_id' => 165,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            357 =>
            array(
                'id' => 874,
                'discipline_id' => 49,
                'discipline_similaire_id' => 32,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            358 =>
            array(
                'id' => 875,
                'discipline_id' => 49,
                'discipline_similaire_id' => 45,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            359 =>
            array(
                'id' => 876,
                'discipline_id' => 49,
                'discipline_similaire_id' => 50,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            360 =>
            array(
                'id' => 877,
                'discipline_id' => 49,
                'discipline_similaire_id' => 53,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            361 =>
            array(
                'id' => 878,
                'discipline_id' => 49,
                'discipline_similaire_id' => 58,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            362 =>
            array(
                'id' => 879,
                'discipline_id' => 49,
                'discipline_similaire_id' => 59,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            363 =>
            array(
                'id' => 880,
                'discipline_id' => 49,
                'discipline_similaire_id' => 72,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            364 =>
            array(
                'id' => 881,
                'discipline_id' => 49,
                'discipline_similaire_id' => 73,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            365 =>
            array(
                'id' => 882,
                'discipline_id' => 1,
                'discipline_similaire_id' => 0,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            366 =>
            array(
                'id' => 885,
                'discipline_id' => 122,
                'discipline_similaire_id' => 308,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            367 =>
            array(
                'id' => 886,
                'discipline_id' => 195,
                'discipline_similaire_id' => 204,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            368 =>
            array(
                'id' => 887,
                'discipline_id' => 195,
                'discipline_similaire_id' => 213,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            369 =>
            array(
                'id' => 888,
                'discipline_id' => 152,
                'discipline_similaire_id' => 304,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            370 =>
            array(
                'id' => 889,
                'discipline_id' => 152,
                'discipline_similaire_id' => 83,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            371 =>
            array(
                'id' => 890,
                'discipline_id' => 152,
                'discipline_similaire_id' => 163,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            372 =>
            array(
                'id' => 891,
                'discipline_id' => 152,
                'discipline_similaire_id' => 320,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            373 =>
            array(
                'id' => 892,
                'discipline_id' => 152,
                'discipline_similaire_id' => 257,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            374 =>
            array(
                'id' => 893,
                'discipline_id' => 246,
                'discipline_similaire_id' => 245,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            375 =>
            array(
                'id' => 894,
                'discipline_id' => 246,
                'discipline_similaire_id' => 243,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            376 =>
            array(
                'id' => 895,
                'discipline_id' => 103,
                'discipline_similaire_id' => 113,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            377 =>
            array(
                'id' => 896,
                'discipline_id' => 317,
                'discipline_similaire_id' => 225,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            378 =>
            array(
                'id' => 897,
                'discipline_id' => 317,
                'discipline_similaire_id' => 227,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            379 =>
            array(
                'id' => 898,
                'discipline_id' => 317,
                'discipline_similaire_id' => 223,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            380 =>
            array(
                'id' => 899,
                'discipline_id' => 317,
                'discipline_similaire_id' => 224,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            381 =>
            array(
                'id' => 900,
                'discipline_id' => 1,
                'discipline_similaire_id' => 18,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            382 =>
            array(
                'id' => 901,
                'discipline_id' => 3,
                'discipline_similaire_id' => 27,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            383 =>
            array(
                'id' => 902,
                'discipline_id' => 3,
                'discipline_similaire_id' => 30,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            384 =>
            array(
                'id' => 903,
                'discipline_id' => 98,
                'discipline_similaire_id' => 96,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            385 =>
            array(
                'id' => 904,
                'discipline_id' => 9,
                'discipline_similaire_id' => 313,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            386 =>
            array(
                'id' => 905,
                'discipline_id' => 12,
                'discipline_similaire_id' => 30,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            387 =>
            array(
                'id' => 906,
                'discipline_id' => 15,
                'discipline_similaire_id' => 31,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            388 =>
            array(
                'id' => 907,
                'discipline_id' => 125,
                'discipline_similaire_id' => 307,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            389 =>
            array(
                'id' => 908,
                'discipline_id' => 125,
                'discipline_similaire_id' => 343,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            390 =>
            array(
                'id' => 909,
                'discipline_id' => 125,
                'discipline_similaire_id' => 133,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            391 =>
            array(
                'id' => 910,
                'discipline_id' => 249,
                'discipline_similaire_id' => 270,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            392 =>
            array(
                'id' => 911,
                'discipline_id' => 249,
                'discipline_similaire_id' => 251,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            393 =>
            array(
                'id' => 912,
                'discipline_id' => 248,
                'discipline_similaire_id' => 247,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            394 =>
            array(
                'id' => 913,
                'discipline_id' => 269,
                'discipline_similaire_id' => 272,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            395 =>
            array(
                'id' => 914,
                'discipline_id' => 269,
                'discipline_similaire_id' => 275,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            396 =>
            array(
                'id' => 915,
                'discipline_id' => 130,
                'discipline_similaire_id' => 148,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            397 =>
            array(
                'id' => 916,
                'discipline_id' => 251,
                'discipline_similaire_id' => 249,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            398 =>
            array(
                'id' => 917,
                'discipline_id' => 251,
                'discipline_similaire_id' => 270,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            399 =>
            array(
                'id' => 918,
                'discipline_id' => 251,
                'discipline_similaire_id' => 257,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            400 =>
            array(
                'id' => 919,
                'discipline_id' => 251,
                'discipline_similaire_id' => 130,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            401 =>
            array(
                'id' => 920,
                'discipline_id' => 251,
                'discipline_similaire_id' => 319,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            402 =>
            array(
                'id' => 921,
                'discipline_id' => 311,
                'discipline_similaire_id' => 8,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            403 =>
            array(
                'id' => 922,
                'discipline_id' => 311,
                'discipline_similaire_id' => 6,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            404 =>
            array(
                'id' => 923,
                'discipline_id' => 311,
                'discipline_similaire_id' => 122,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            405 =>
            array(
                'id' => 924,
                'discipline_id' => 311,
                'discipline_similaire_id' => 185,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            406 =>
            array(
                'id' => 925,
                'discipline_id' => 106,
                'discipline_similaire_id' => 344,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            407 =>
            array(
                'id' => 926,
                'discipline_id' => 106,
                'discipline_similaire_id' => 345,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            408 =>
            array(
                'id' => 927,
                'discipline_id' => 106,
                'discipline_similaire_id' => 347,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            409 =>
            array(
                'id' => 928,
                'discipline_id' => 106,
                'discipline_similaire_id' => 107,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            410 =>
            array(
                'id' => 929,
                'discipline_id' => 257,
                'discipline_similaire_id' => 251,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            411 =>
            array(
                'id' => 930,
                'discipline_id' => 257,
                'discipline_similaire_id' => 249,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            412 =>
            array(
                'id' => 931,
                'discipline_id' => 257,
                'discipline_similaire_id' => 314,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            413 =>
            array(
                'id' => 932,
                'discipline_id' => 257,
                'discipline_similaire_id' => 319,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            414 =>
            array(
                'id' => 933,
                'discipline_id' => 344,
                'discipline_similaire_id' => 107,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            415 =>
            array(
                'id' => 934,
                'discipline_id' => 344,
                'discipline_similaire_id' => 116,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            416 =>
            array(
                'id' => 935,
                'discipline_id' => 344,
                'discipline_similaire_id' => 104,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            417 =>
            array(
                'id' => 936,
                'discipline_id' => 344,
                'discipline_similaire_id' => 106,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            418 =>
            array(
                'id' => 937,
                'discipline_id' => 344,
                'discipline_similaire_id' => 347,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            419 =>
            array(
                'id' => 938,
                'discipline_id' => 344,
                'discipline_similaire_id' => 112,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            420 =>
            array(
                'id' => 939,
                'discipline_id' => 344,
                'discipline_similaire_id' => 108,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            421 =>
            array(
                'id' => 940,
                'discipline_id' => 344,
                'discipline_similaire_id' => 118,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            422 =>
            array(
                'id' => 941,
                'discipline_id' => 344,
                'discipline_similaire_id' => 346,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            423 =>
            array(
                'id' => 942,
                'discipline_id' => 256,
                'discipline_similaire_id' => 10,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            424 =>
            array(
                'id' => 943,
                'discipline_id' => 256,
                'discipline_similaire_id' => 251,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            425 =>
            array(
                'id' => 944,
                'discipline_id' => 256,
                'discipline_similaire_id' => 249,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            426 =>
            array(
                'id' => 945,
                'discipline_id' => 256,
                'discipline_similaire_id' => 314,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            427 =>
            array(
                'id' => 946,
                'discipline_id' => 303,
                'discipline_similaire_id' => 108,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            428 =>
            array(
                'id' => 947,
                'discipline_id' => 299,
                'discipline_similaire_id' => 2,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            429 =>
            array(
                'id' => 948,
                'discipline_id' => 2,
                'discipline_similaire_id' => 299,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            430 =>
            array(
                'id' => 949,
                'discipline_id' => 299,
                'discipline_similaire_id' => 25,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            431 =>
            array(
                'id' => 950,
                'discipline_id' => 281,
                'discipline_similaire_id' => 123,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            432 =>
            array(
                'id' => 951,
                'discipline_id' => 281,
                'discipline_similaire_id' => 121,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            433 =>
            array(
                'id' => 952,
                'discipline_id' => 281,
                'discipline_similaire_id' => 278,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            434 =>
            array(
                'id' => 953,
                'discipline_id' => 281,
                'discipline_similaire_id' => 297,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            435 =>
            array(
                'id' => 954,
                'discipline_id' => 281,
                'discipline_similaire_id' => 108,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            436 =>
            array(
                'id' => 955,
                'discipline_id' => 196,
                'discipline_similaire_id' => 219,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            437 =>
            array(
                'id' => 956,
                'discipline_id' => 196,
                'discipline_similaire_id' => 220,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            438 =>
            array(
                'id' => 957,
                'discipline_id' => 250,
                'discipline_similaire_id' => 249,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            439 =>
            array(
                'id' => 958,
                'discipline_id' => 250,
                'discipline_similaire_id' => 270,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            440 =>
            array(
                'id' => 959,
                'discipline_id' => 250,
                'discipline_similaire_id' => 262,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            441 =>
            array(
                'id' => 960,
                'discipline_id' => 360,
                'discipline_similaire_id' => 225,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            442 =>
            array(
                'id' => 961,
                'discipline_id' => 360,
                'discipline_similaire_id' => 227,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            443 =>
            array(
                'id' => 962,
                'discipline_id' => 360,
                'discipline_similaire_id' => 226,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            444 =>
            array(
                'id' => 963,
                'discipline_id' => 197,
                'discipline_similaire_id' => 206,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            445 =>
            array(
                'id' => 964,
                'discipline_id' => 198,
                'discipline_similaire_id' => 206,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            446 =>
            array(
                'id' => 965,
                'discipline_id' => 198,
                'discipline_similaire_id' => 202,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            447 =>
            array(
                'id' => 966,
                'discipline_id' => 198,
                'discipline_similaire_id' => 207,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            448 =>
            array(
                'id' => 967,
                'discipline_id' => 278,
                'discipline_similaire_id' => 121,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            449 =>
            array(
                'id' => 968,
                'discipline_id' => 278,
                'discipline_similaire_id' => 124,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
            450 =>
            array(
                'id' => 969,
                'discipline_id' => 350,
                'discipline_similaire_id' => 118,
                'created_at' => '2023-06-22 18:07:31',
                'updated_at' => '2023-06-22 18:07:31',
            ),
        ));


    }
}
