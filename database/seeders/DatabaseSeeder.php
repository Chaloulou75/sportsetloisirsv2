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
        $this->call(CategoriesTableSeeder::class);

        $departements = Departement::all();
        $cities = City::all();

        User::factory()->create([
                    'name' => 'Charles J',
                    'email' => 'c.jeandey@gmail.com',
                ]);
        User::factory()->create([
            'name' => 'Tonio V',
            'email' => 'tonio20@hotmail.fr',
        ]);

        $users = User::factory()->count(18)->create();

        //les Types de structure
        Structuretype::factory()->create(['name' =>'Club', 'slug' => 'club']);
        Structuretype::factory()->create(['name' =>'Coach', 'slug' => 'coach']);
        Structuretype::factory()->create(['name' =>'Lieux', 'slug' => 'lieux']);
        Structuretype::factory()->create(['name' =>'Agence', 'slug' => 'agence']);
        Structuretype::factory()->create(['name' =>'Société', 'slug' => 'societe']);

        //les familles
        Famille::factory()->create(['name' => 'Balle', 'slug' => 'balle']);
        Famille::factory()->create(['name' => 'Combat', 'slug' => 'combat']);
        Famille::factory()->create(['name' => 'Montagne', 'slug' => 'montagne']);
        Famille::factory()->create(['name' => 'Eau', 'slug' => 'eau']);
        Famille::factory()->create(['name' => 'Fitness', 'slug' => 'fitness']);
        Famille::factory()->create(['name' => 'Mecanique', 'slug' => 'mecanique']);
        Famille::factory()->create(['name' => 'Musique', 'slug' => 'musique']);
        Famille::factory()->create(['name' => 'Danse', 'slug' => 'danse']);
        Famille::factory()->create(['name' => 'Créations', 'slug' => 'creations']);
        Famille::factory()->create(['name' => 'Jeux - Divers', 'slug' => 'jeux-divers']);

        $familles = Famille::all();

        //balle
        Discipline::factory()->create(['name' => 'Badminton', 'slug' => 'badminton', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Baseball', 'slug' => 'baseball', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Basket-ball', 'slug' => 'basket-ball', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Beach Soccer', 'slug' => 'beach-soccer', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Beach Volley', 'slug' => 'beach-volley', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Cricket', 'slug' => 'cricket', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Flag', 'slug' => 'flag', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Foot 5', 'slug' => 'foot-5', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Football', 'slug' => 'football', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Football Americain', 'slug' => 'football-americain', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Football en Salle', 'slug' => 'football-en-salle', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Golf', 'slug' => 'golf', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Handball', 'slug' => 'handball', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Hockey Subaquatique', 'slug' => 'hockey-subaquatique', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Hockey en Salle', 'slug' => 'hockey-en-salle', 'famille_id' => 1]);

        Discipline::factory()->create(['name' => 'Hockey sur Gazon', 'slug' => 'hockey-sur-gazon', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Hockey sur Glace', 'slug' => 'hockey-sur-glace', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Horse Ball', 'slug' => 'horse-ball', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Jorkyball', 'slug' => 'jorkyball', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Kinball', 'slug' => 'kinball', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Padel', 'slug' => 'padel', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Pelote Basque', 'slug' => 'pelote-basque', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Polo', 'slug' => 'polo', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Pétéca', 'slug' => 'peteca', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Rugby', 'slug' => 'rugby', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Sandball', 'slug' => 'sandball', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Softball', 'slug' => 'softball', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Squash', 'slug' => 'squash', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Tchoukball', 'slug' => 'tchoukball', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Tennis', 'slug' => 'tennis', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Tennis de Table', 'slug' => 'tennis-de-table', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Volley-ball', 'slug' => 'volley-ball', 'famille_id' => 1]);
        Discipline::factory()->create(['name' => 'Water-Polo', 'slug' => 'water-polo', 'famille_id' => 1]);
        //combat
        Discipline::factory()->create(['name' => 'Aikido', 'slug' => 'aikido', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Baton Defense', 'slug' => 'baton-defense', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Battodo', 'slug' => 'battodo', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Boxe Anglaise', 'slug' => 'boxe-anglaise', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Boxe Chinoise', 'slug' => 'boxe-chinoise', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Boxe Française', 'slug' => 'boxe-française', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Boxe Thailandaise', 'slug' => 'boxe-thailandaise', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Canne Defense', 'slug' => 'canne-defense', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Canne De Combat', 'slug' => 'canne-de-combat', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Capoera', 'slug' => 'capoera', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Catch', 'slug' => 'catch', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Chanbara', 'slug' => 'chanbara', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Escrime', 'slug' => 'escrime', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Free Fight', 'slug' => 'free-fight', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Full Contact', 'slug' => 'full-contact', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Gouren', 'slug' => 'gouren', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Grappling', 'slug' => 'grappling', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Hapkido', 'slug' => 'hapkido', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Iaido', 'slug' => 'iaido', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Jeet Kune Do', 'slug' => 'jeet-kune-do', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Jodo', 'slug' => 'jodo', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Ju-jitsu', 'slug' => 'ju-jitsu', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Ju-jitsu Brésilien', 'slug' => 'ju-jitsu-bresilien', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Judo', 'slug' => 'judo', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'K-One', 'slug' => 'k-one', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Kalarippayatt', 'slug' => 'kalarippayatt', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Kali Escrima', 'slug' => 'kali-escrima', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Kapap', 'slug' => 'kapap', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Karaté', 'slug' => 'karate', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Kendo', 'slug' => 'kendo', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Kenjutsu', 'slug' => 'kenjutsu', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Kobudo', 'slug' => 'kobudo', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Krav-Maga', 'slug' => 'krav-maga', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Kung-Fu', 'slug' => 'kung-fu', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Kyudo', 'slug' => 'kyudo', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Lutte', 'slug' => 'lutte', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Naginata', 'slug' => 'naginata', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Ninjutsu', 'slug' => 'ninjutsu', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Nunchaku', 'slug' => 'nunchaku', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Pa Kua', 'slug' => 'pa-kua', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Pancrate', 'slug' => 'pancrate', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Penchak-Silat', 'slug' => 'penchak-silat', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Qwan Ki Do', 'slug' => 'Qwan-ki-do', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'ROS', 'slug' => 'ros', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Sambo', 'slug' => 'sambo', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Sarbacana', 'slug' => 'sarbacana', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Self Défense', 'slug' => 'self-defense', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Shuai Jiao', 'slug' => 'shuai-jiao', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Sumo', 'slug' => 'sumo', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Systema', 'slug' => 'systema', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Taekwondo', 'slug' => 'taekwondo', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Tai-chi-chuan', 'slug' => 'tai-chi-chuan', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Tai-jitsu', 'slug' => 'tai-jitsu', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Thaing Bando', 'slug' => 'thaing-bando', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Viet Vo Dao', 'slug' => 'viet-vo-dao', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Wing Chun', 'slug' => 'wing-chun', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Yi-Quan', 'slug' => 'yi-quan', 'famille_id' => 2]);
        Discipline::factory()->create(['name' => 'Yoseikan Budo', 'slug' => 'yoseikan-budo', 'famille_id' => 2]);

        //Montagne
        Discipline::factory()->create(['name' => 'Accrobranche', 'slug' => 'accrobranche', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Agility', 'slug' => 'agility', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Air-Boat', 'slug' => 'air-boat', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Alpinisme', 'slug' => 'alpinisme', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Ball Trap', 'slug' => 'ball-trap', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Bobsleigh', 'slug' => 'bobsleigh', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Curling', 'slug' => 'curling', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Deltaplane', 'slug' => 'deltaplane', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Equitation', 'slug' => 'equitation', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Escalade', 'slug' => 'escalade', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Luge', 'slug' => 'luge', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Marche Nordique', 'slug' => 'marche-nordique', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Motoneige', 'slug' => 'motoneige', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Mountainboard', 'slug' => 'mountainboard', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Parachutisme', 'slug' => 'parachutisme', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Parapente', 'slug' => 'parapente', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Patin à Glace', 'slug' => 'patin-a-glace', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Patinage Artistique', 'slug' => 'patinage-artistique', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Patinage de Vitesse', 'slug' => 'patinage-de-vitesse', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Randonnée', 'slug' => 'randonnee', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Randonnée Equestre', 'slug' => 'randonnee-equestre', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Raquettes', 'slug' => 'raquettes', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Saut à l\'Elastique', 'slug' => 'saut-a-l-elastique', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Sauvetage', 'slug' => 'sauvetage', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Short Track', 'slug' => 'short-track', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Ski Alpin', 'slug' => 'ski-alpin', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Ski de Fond', 'slug' => 'ski-de-fond', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Ski de Vitesse', 'slug' => 'ski-de-vitesse', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Snowboard', 'slug' => 'snowboard', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Spéléologie', 'slug' => 'speleologie', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Tir Sportif', 'slug' => 'tir-sportif', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Tir à l\'Arc', 'slug' => 'tir-a-l-arc', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Traineau', 'slug' => 'traineau', 'famille_id' => 3]);
        Discipline::factory()->create(['name' => 'Via Ferrata', 'slug' => 'via-ferrata', 'famille_id' => 3]);

        //Eau
        Discipline::factory()->create(['name' => 'Apnée', 'slug' => 'apnee', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Aviron', 'slug' => 'aviron', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Bébés Nageurs', 'slug' => 'bebes-nageurs', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Canoé-kayak', 'slug' => 'canoe-kayak', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Canyoning', 'slug' => 'canyoning', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Char à Voile', 'slug' => 'char-a-voile', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Hydrospeed', 'slug' => 'hydrospeed', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Jet Ski', 'slug' => 'jet-ski', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Natation', 'slug' => 'natation', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Natation Synchronisée', 'slug' => 'natation-synchronisee', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Planche à Voile', 'slug' => 'planche-a-voile', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Plongeon', 'slug' => 'plongeon', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Plongée', 'slug' => 'plongee', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Pêche', 'slug' => 'peche', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Pêche Sous Marine', 'slug' => 'peche sous marine', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Rafting', 'slug' => 'rafting', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Ski Nautique', 'slug' => 'ski-nautique', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Snorkeling', 'slug' => 'snorkeling', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Surf', 'slug' => 'surf', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Voile', 'slug' => 'voile', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Wakeboard', 'slug' => 'wakeboard', 'famille_id' => 4]);
        Discipline::factory()->create(['name' => 'Windsurf', 'slug' => 'windsurf', 'famille_id' => 4]);
        //fitness
        Discipline::factory()->create(['name' => 'Aquabiking Vélo Aquatique', 'slug' => 'aquabiking-velo-aquatique', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Aquaboxing', 'slug' => 'aquaboxing', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Aquafitness', 'slug' => 'aquafitness', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Aquagym', 'slug' => 'aquagym', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Aquajogging', 'slug' => 'aquajogging', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Athlétisme', 'slug' => 'athletisme', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Aérobic Sportive', 'slug' => 'aerobic-sportive', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Cardiotraining', 'slug' => 'cardiotraining', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Cirque', 'slug' => 'cirque', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Course à pied', 'slug' => 'course-a-pied', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Dao Yin', 'slug' => 'dao-yin', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Fitness', 'slug' => 'fitness', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Gym Suédoise', 'slug' => 'gym-suedoise', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Gymnastique', 'slug' => 'gymnastique', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Gymnastique Rythmique', 'slug' => 'gymnastique-rythmique', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Musculation', 'slug' => 'musculation', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Pilates', 'slug' => 'pilates', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Qi Gong', 'slug' => 'qi-gong', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Yoga', 'slug' => 'yoga', 'famille_id' => 5]);
        Discipline::factory()->create(['name' => 'Zumba', 'slug' => 'zumba', 'famille_id' => 5]);

        //mecanique
        Discipline::factory()->create(['name' => 'Aviation', 'slug' => 'aviation', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'Aérostation', 'slug' => 'aerostation', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'BMX', 'slug' => 'bmx', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'Cyclisme sur Piste', 'slug' => 'cyclisme-sur-piste', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'Cyclisme sur Route', 'slug' => 'cyclisme-sur-route', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'Cyclocross', 'slug' => 'cyclocross', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'Cyclotourisme', 'slug' => 'cyclotourisme', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'Giraviation', 'slug' => 'giraviation', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'Karting', 'slug' => 'karting', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'Moto Vitesse', 'slug' => 'moto-vitesse', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'Motocross', 'slug' => 'motocross', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'Paramoteur', 'slug' => 'paramoteur', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'Quad', 'slug' => 'quad', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'Rallye', 'slug' => 'rallye', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'Rollers', 'slug' => 'rollers', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'Skate', 'slug' => 'skate', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'ULM', 'slug' => 'ulm', 'famille_id' => 6]);
        Discipline::factory()->create(['name' => 'VTT', 'slug' => 'vtt', 'famille_id' => 6]);

        //Musique
        Discipline::factory()->create(['name' => 'Accordéon', 'slug' => 'accordeon', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Alto', 'slug' => 'alto', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Banjo', 'slug' => 'banjo', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Basse', 'slug' => 'basse', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Batterie', 'slug' => 'batterie', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Chant', 'slug' => 'chant', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Chorale', 'slug' => 'chorale', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Clarinette', 'slug' => 'clarinette', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Clavecin', 'slug' => 'clavecin', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Contrebasse', 'slug' => 'contrebasse', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Cor', 'slug' => 'cor', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Cornemuse', 'slug' => 'cornemuse', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Djembé', 'slug' => 'djembe', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Flûte à Bec', 'slug' => 'flute-a-bec', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Guitare', 'slug' => 'guitare', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Guitare Electrique', 'slug' => 'guitare-electrique', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Harmonica', 'slug' => 'harmonica', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Harpe', 'slug' => 'harpe', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Hautbois', 'slug' => 'hautbois', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Orgues', 'slug' => 'orgues', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Piano', 'slug' => 'piano', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Platines', 'slug' => 'platines', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Saxophone', 'slug' => 'saxophone', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Trombone', 'slug' => 'trombone', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Trompette', 'slug' => 'trompette', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Tuba', 'slug' => 'tuba', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Violon', 'slug' => 'violon', 'famille_id' => 7]);
        Discipline::factory()->create(['name' => 'Violoncelle', 'slug' => 'violoncelle', 'famille_id' => 7]);

        //Danse
        Discipline::factory()->create(['name' => 'Cheerleading', 'slug' => 'cheerleading', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Claquettes', 'slug' => 'claquettes', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Country', 'slug' => 'country', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Danse Africaine', 'slug' => 'danse-africaine', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Danse Classique', 'slug' => 'danse-classique', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Danse Espagnole', 'slug' => 'danse-espagnole', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Danse Folklorique', 'slug' => 'danse-folklorique', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Danse Indienne', 'slug' => 'danse-indienne', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Danse Orientale', 'slug' => 'danse-orientale', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Danse Tahitienne', 'slug' => 'danse-tahitienne', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Danse de Salon', 'slug' => 'danse-de-salon', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Flamenco', 'slug' => 'flamenco', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Hip-Hop', 'slug' => 'hip-hop', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Modern Jazz', 'slug' => 'modern-jazz', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Pole Dance', 'slug' => 'pole-dance', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Rock', 'slug' => 'rock', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Salsa', 'slug' => 'salsa', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Samba', 'slug' => 'samba', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Swing', 'slug' => 'swing', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Tango', 'slug' => 'tango', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Tribal Fusion', 'slug' => 'tribal-fusion', 'famille_id' => 8]);
        Discipline::factory()->create(['name' => 'Valse', 'slug' => 'valse', 'famille_id' => 8]);

        //loisirs-creatifs
        Discipline::factory()->create(['name' => 'Dessin', 'slug' => 'dessin', 'famille_id' => 9]);
        Discipline::factory()->create(['name' => 'Ecriture', 'slug' => 'ecriture', 'famille_id' => 9]);
        Discipline::factory()->create(['name' => 'Graphisme', 'slug' => 'graphisme', 'famille_id' => 9]);
        Discipline::factory()->create(['name' => 'Peinture', 'slug' => 'peinture', 'famille_id' => 9]);
        Discipline::factory()->create(['name' => 'Photographie', 'slug' => 'photographie', 'famille_id' => 9]);
        Discipline::factory()->create(['name' => 'Poterie', 'slug' => 'poterie', 'famille_id' => 9]);
        Discipline::factory()->create(['name' => 'Sculpture', 'slug' => 'sculpture', 'famille_id' => 9]);
        Discipline::factory()->create(['name' => 'Theâtre', 'slug' => 'theatre', 'famille_id' => 9]);
        Discipline::factory()->create(['name' => 'Vidéo', 'slug' => 'video', 'famille_id' => 9]);

        //jeux-divers
        Discipline::factory()->create(['name' => 'Aéromodélisme', 'slug' => 'aeromodelisme', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Babyfoot', 'slug' => 'babyfoot', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Belotte', 'slug' => 'belotte', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Billards', 'slug' => 'billards', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Boomrang', 'slug' => 'boomrang', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Bowling', 'slug' => 'bowling', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Bridge', 'slug' => 'bridge', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Cartes', 'slug' => 'cartes', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Cerf-volant', 'slug' => 'cerf-volant', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Course d\'Orientation', 'slug' => 'course-d-orientation', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Cuisine', 'slug' => 'cuisine', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Echecs', 'slug' => 'echecs', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Escape Game', 'slug' => 'escape-game', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Fléchettes', 'slug' => 'flechettes', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Frisbee', 'slug' => 'frisbee', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Jeux Vidéos', 'slug' => 'jeux-videos', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Jeux de Roles', 'slug' => 'jeux-de-roles', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Jeux de Société', 'slug' => 'jeux-de-societe', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Laser Game', 'slug' => 'laser-game', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Littérature', 'slug' => 'litterature', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Magie', 'slug' => 'magie', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Massage', 'slug' => 'massage', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Minigolf', 'slug' => 'minigolf', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Modélisme', 'slug' => 'modelisme', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Paintball', 'slug' => 'paintball', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Philatélie', 'slug' => 'philatelie', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Poker', 'slug' => 'poker', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Pédalo', 'slug' => 'pedalo', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Pétanque', 'slug' => 'petanque', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Rire', 'slug' => 'rire', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Saunas Hamams', 'slug' => 'saunas-hamams', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Scrabble', 'slug' => 'scrabble', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Tarot', 'slug' => 'tarot', 'famille_id' => 10]);
        Discipline::factory()->create(['name' => 'Thalassothérapie', 'slug' => 'thalassotherapie', 'famille_id' => 10]);

        $disciplines= Discipline::select('id')->get();

        //les types d'activités
        Activitetype::factory()->create(['name' =>'Cours à l\'année', 'slug' => 'cours']);
        Activitetype::factory()->create(['name' =>'Sortie accompagnée', 'slug' => 'sortie']);
        Activitetype::factory()->create(['name' =>'Competitions', 'slug' => 'competitions']);
        Activitetype::factory()->create(['name' =>'Stages', 'slug' => 'stages']);
        Activitetype::factory()->create(['name' =>'Séjours', 'slug' => 'sejours']);

        //les Niveaux
        Nivel::factory()->create(['name' =>'Tous niveaux', 'slug' => 'tous-niveaux']);
        Nivel::factory()->create(['name' =>'Novice', 'slug' => 'novice']);
        Nivel::factory()->create(['name' =>'Débutant', 'slug' => 'debutant']);
        Nivel::factory()->create(['name' =>'Intermédiaire', 'slug' => 'intermediaire']);
        Nivel::factory()->create(['name' =>'Avancé', 'slug' => 'avance']);
        Nivel::factory()->create(['name' =>'Expert', 'slug' => 'expert']);

        //les publics
        Publictype::factory()->create(['name' =>'Tous âges', 'slug' => 'tous-ages']);
        Publictype::factory()->create(['name' =>'3 ans', 'slug' => '3ans']);
        Publictype::factory()->create(['name' =>'3 à 6 ans', 'slug' => '3-6ans']);
        Publictype::factory()->create(['name' =>'6 à 8 ans', 'slug' => '6-8ans']);
        Publictype::factory()->create(['name' =>'8 à 10 ans', 'slug' => '8-10ans']);
        Publictype::factory()->create(['name' =>'10 à 12 ans', 'slug' => '10-12ans']);
        Publictype::factory()->create(['name' =>'12 à 14 ans', 'slug' => '12-14ans']);
        Publictype::factory()->create(['name' =>'14 à 18 ans', 'slug' => '14-18ans']);
        Publictype::factory()->create(['name' =>'Adultes', 'slug' => 'adultes']);

        Structure::factory(12)->create();
    }
}
