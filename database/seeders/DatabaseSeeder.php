<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Category;
use App\Models\Structure;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        User::factory()->create([
                    'name' => 'Charles J',
                    'email' => 'c.jeandey@gmail.com',
                ]);
        User::factory()->create([
            'name' => 'Tonio V',
            'email' => 'tonio20@hotmail.fr',
        ]);

        $users = User::factory()->count(18)->create();

        //les structures
        Structure::factory()->create(['name' =>'Club', 'slug' => 'club']);
        Structure::factory()->create(['name' =>'Coach', 'slug' => 'coach']);
        Structure::factory()->create(['name' =>'Lieux', 'slug' => 'lieux']);
        Structure::factory()->create(['name' =>'Agence', 'slug' => 'agence']);


        //les categories
        Category::factory()->create(['name' => 'Balle', 'slug' => 'balle']);
        Category::factory()->create(['name' => 'Combat', 'slug' => 'combat']);
        Category::factory()->create(['name' => 'Montagne', 'slug' => 'montagne']);
        Category::factory()->create(['name' => 'Eau', 'slug' => 'eau']);
        Category::factory()->create(['name' => 'Fitness', 'slug' => 'fitness']);
        Category::factory()->create(['name' => 'Mecanique', 'slug' => 'mecanique']);
        Category::factory()->create(['name' => 'Musique', 'slug' => 'musique']);
        Category::factory()->create(['name' => 'Danse', 'slug' => 'danse']);
        Category::factory()->create(['name' => 'Créations', 'slug' => 'creations']);
        Category::factory()->create(['name' => 'Jeux - Divers', 'slug' => 'jeux-divers']);

        $categories = Category::all();
    }
}
