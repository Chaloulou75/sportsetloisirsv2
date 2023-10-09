<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VilleFranceSlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('villes_france')->get()->each(function ($row) {
            $formattedVille = Str::slug(str_replace('+', '-', $row->ville_formatee));
            $slug = $formattedVille . '-' . $row->id . '-1';

            DB::table('villes_france')
                ->where('id', $row->id)
                ->update(['slug' => $slug]);
        });

    }
}
