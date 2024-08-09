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
            ['type' => 'select', 'criterable' => 1, 'sous_criterable' => 1, 'created_at' => $now],
            ['type' => 'radio', 'criterable' => 1, 'sous_criterable' => 1, 'created_at' => $now],
            ['type' => 'checkbox', 'criterable' => 1, 'sous_criterable' => 1, 'created_at' => $now],
            ['type' => 'range', 'criterable' => 1, 'sous_criterable' => 0, 'created_at' => $now],
            ['type' => 'range multiple', 'criterable' => 1, 'sous_criterable' => 0, 'created_at' => $now],
            ['type' => 'text', 'criterable' => 1, 'sous_criterable' => 0, 'created_at' => $now],
            ['type' => 'number', 'criterable' => 1, 'sous_criterable' => 0, 'created_at' => $now],
            ['type' => 'date', 'criterable' => 1, 'sous_criterable' => 0, 'created_at' => $now],
            ['type' => 'dates', 'criterable' => 1, 'sous_criterable' => 0, 'created_at' => $now],
            ['type' => 'time', 'criterable' => 1, 'sous_criterable' => 0, 'created_at' => $now],
            ['type' => 'times', 'criterable' => 1, 'sous_criterable' => 0, 'created_at' => $now],
            ['type' => 'mois', 'criterable' => 1, 'sous_criterable' => 0, 'created_at' => $now],
            ['type' => 'adresse', 'criterable' => 0, 'sous_criterable' => 0, 'created_at' => $now],
            ['type' => 'instructeur', 'criterable' => 0,'sous_criterable' => 0, 'created_at' => $now],
        ];

        DB::table('type_champs')->insert($typeChamps);

    }
}
