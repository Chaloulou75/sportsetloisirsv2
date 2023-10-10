<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LienDisciplineCategorieSlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('liens_disciplines_categories')->get()->each(function ($row) {
            $formattedDisCat = Str::slug(str_replace('+', '-', $row->nom_categorie_client));
            $slug = $formattedDisCat . '-' . $row->id;

            DB::table('liens_disciplines_categories')
                ->where('id', $row->id)
                ->update(['slug' => $slug]);
        });

    }
}
