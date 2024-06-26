<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\LienDisciplineSimilaire;
use Database\Seeders\AListePaysTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\TypeChampsTableSeeder;
use Database\Seeders\VilleFranceSlugSeeder;
use Database\Seeders\DepartementsSlugSeeder;
use Database\Seeders\DepartementsTableSeeder;
use Database\Seeders\VillesFranceTableSeeder;
use Database\Seeders\ListeCriteresTableSeeder;
use Database\Seeders\ListeFamillesTableSeeder;
use Database\Seeders\StructuretypesTableSeeder;
use Database\Seeders\ListeDisciplinesTableSeeder;
use Database\Seeders\ListeTarifsTypesTableSeeder;
use Database\Seeders\LienDisciplineCategorieSlugSeeder;
use Database\Seeders\LiensFamillesDisciplinesTableSeeder;
use Database\Seeders\ListeTarifsTypesAttributsTableSeeder;
use Database\Seeders\LiensDisciplinesCategoriesTableSeeder;
use Database\Seeders\LiensDisciplinesSimilairesTableSeeder;
use Database\Seeders\LiensDisCatCritValSousCriteresTableSeeder;
use Database\Seeders\LiensDisCatCritValSsCritValeurTableSeeder;
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
        $this->call(DepartementsSlugSeeder::class);
        $this->call(VillesFranceTableSeeder::class);
        $this->call(VilleFranceSlugSeeder::class);
        $this->call(ListeFamillesTableSeeder::class);
        $this->call(ListeDisciplinesTableSeeder::class);
        $this->call(ListeCriteresTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(StructuretypesTableSeeder::class);
        $this->call(TypeChampsTableSeeder::class);
        $this->call(ListeStructuresTypesAttributsTableSeeder::class);
        $this->call(ListeStructuresTypesValeursTableSeeder::class);
        $this->call(ListeTarifsTypesTableSeeder::class);
        $this->call(ListeTarifsTypesAttributsTableSeeder::class);
        $this->call(LiensDisciplinesCategoriesTableSeeder::class);
        $this->call(LienDisciplineCategorieSlugSeeder::class);
        $this->call(LiensDisciplinesCategoriesCriteresTableSeeder::class);
        $this->call(LiensDisciplinesCategoriesCriteresValeursTableSeeder::class);
        $this->call(LiensDisCatCritValSousCriteresTableSeeder::class);
        $this->call(LiensDisCatCritValSsCritValeurTableSeeder::class);
        // $this->call(LiensDisciplinesSimilairesTableSeeder::class);
        // $this->call(LiensFamillesDisciplinesTableSeeder::class);
        $this->call(RoleSeeder::class);
        User::factory()->create([
                    'name' => 'Charles J',
                    'email' => 'c.jeandey@gmail.com',
                ]);
        User::factory()->create([
            'name' => 'Tonio V',
            'email' => 'tonio20@hotmail.fr',
        ]);

        $this->call(LiensDisCatCritValSsCritValeurTableSeeder::class);
        $this->call(LiensDisCatCritValSousCriteresTableSeeder::class);
    }
}
