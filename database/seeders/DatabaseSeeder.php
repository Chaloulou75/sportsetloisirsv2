<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\City;
use App\Models\User;
use App\Models\Nivel;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Discipline;
use App\Models\Publictype;
use App\Models\Departement;
use App\Models\Activitetype;
use App\Models\Structuretype;
use Illuminate\Database\Seeder;
use Database\Seeders\AListePaysTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\DepartementsTableSeeder;
use Database\Seeders\VillesFranceTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // $this->call(RegionsTableSeeder::class);

        $this->call(AListePaysTableSeeder::class);
        $this->call(DepartementsTableSeeder::class);
        $this->call(VillesFranceTableSeeder::class);
        // $this->call(CategoriesTableSeeder::class);

        // $departements = Departement::all();
        // $cities = City::all();

        User::factory()->create([
                    'name' => 'Charles J',
                    'email' => 'c.jeandey@gmail.com',
                ]);
        User::factory()->create([
            'name' => 'Tonio V',
            'email' => 'tonio20@hotmail.fr',
        ]);

    }
}
