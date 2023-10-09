<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartementsSlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('departements')->get()->each(function ($row) {
            $formattedDpt = Str::slug(str_replace('+', '-', $row->departement));
            $slug = $formattedDpt . '-' . $row->id . '-2';

            DB::table('departements')
                ->where('id', $row->id)
                ->update(['slug' => $slug]);
        });

    }
}
