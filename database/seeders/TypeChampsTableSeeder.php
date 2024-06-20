<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypeChampsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $now = Carbon::now();

        $typeChamps = [
            ['type' => 'select', 'created_at' => $now],
            ['type' => 'checkbox', 'created_at' => $now],
            ['type' => 'text', 'created_at' => $now],
            ['type' => 'number', 'created_at' => $now],
            ['type' => 'adresse', 'created_at' => $now],
            ['type' => 'date', 'created_at' => $now],
            ['type' => 'dates', 'created_at' => $now],
            ['type' => 'time', 'created_at' => $now],
            ['type' => 'times', 'created_at' => $now],
            ['type' => 'mois', 'created_at' => $now],
            ['type' => 'rayon', 'created_at' => $now],
            ['type' => 'instructeur', 'created_at' => $now],
            ['type' => 'range', 'created_at' => $now],
        ];

        DB::table('type_champs')->insert($typeChamps);

    }
}
