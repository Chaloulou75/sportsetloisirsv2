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
            ['type' => 'select', 'criterable' => 'true', 'created_at' => $now],
            ['type' => 'checkbox', 'criterable' => 'true', 'created_at' => $now],
            ['type' => 'text', 'criterable' => 'true', 'created_at' => $now],
            ['type' => 'number', 'criterable' => 'true', 'created_at' => $now],
            ['type' => 'adresse', 'criterable' => 'false', 'created_at' => $now],
            ['type' => 'date', 'criterable' => 'true', 'created_at' => $now],
            ['type' => 'dates', 'criterable' => 'true', 'created_at' => $now],
            ['type' => 'time', 'criterable' => 'true', 'created_at' => $now],
            ['type' => 'times', 'criterable' => 'true', 'created_at' => $now],
            ['type' => 'mois', 'criterable' => 'true', 'created_at' => $now],
            ['type' => 'instructeur', 'criterable' => 'false', 'created_at' => $now],
            ['type' => 'range', 'criterable' => 'true', 'created_at' => $now],
            ['type' => 'radio', 'criterable' => 'true', 'created_at' => $now],
        ];

        DB::table('type_champs')->insert($typeChamps);

    }
}
