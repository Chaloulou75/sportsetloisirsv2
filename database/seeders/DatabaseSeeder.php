<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\LienDisciplineSimilaire;
use Database\Seeders\AListePaysTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\DepartementsTableSeeder;
use Database\Seeders\VillesFranceTableSeeder;
use Database\Seeders\ListeFamillesTableSeeder;
use Database\Seeders\StructuretypesTableSeeder;
use Database\Seeders\ListeDisciplinesTableSeeder;
use Database\Seeders\ListeTarifsTypesTableSeeder;
use Database\Seeders\LiensFamillesDisciplinesTableSeeder;
use Database\Seeders\ListeTarifsTypesAttributsTableSeeder;
use Database\Seeders\LiensDisciplinesCategoriesTableSeeder;
use Database\Seeders\LiensDisciplinesSimilairesTableSeeder;
use Database\Seeders\LiensDisciplinesCategoriesCriteresTableSeeder;
use Database\Seeders\LiensDisciplinesCategoriesCriteresValeursTableSeeder;

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
        $this->call(ListeFamillesTableSeeder::class);
        $this->call(ListeDisciplinesTableSeeder::class);
        $this->call(StructuretypesTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ListeTarifsTypesTableSeeder::class);
        $this->call(ListeTarifsTypesAttributsTableSeeder::class);
        $this->call(LiensDisciplinesCategoriesTableSeeder::class);
        $this->call(LiensFamillesDisciplinesTableSeeder::class);
        $this->call(LiensDisciplinesSimilairesTableSeeder::class);
        $this->call(LiensDisciplinesCategoriesCriteresTableSeeder::class);
        $this->call(LiensDisciplinesCategoriesCriteresValeursTableSeeder::class);



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
