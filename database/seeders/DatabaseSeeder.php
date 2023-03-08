<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Activite;
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

        //balle
        Activite::factory()->create(['name' => 'Badminton', 'slug' => 'badminton', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Baseball', 'slug' => 'baseball', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Basket-ball', 'slug' => 'basket-ball', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Beach Soccer', 'slug' => 'beach-soccer', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Beach Volley', 'slug' => 'beach-volley', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Beach Soccer', 'slug' => 'beach-soccer', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Cricket', 'slug' => 'cricket', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Flag', 'slug' => 'flag', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Foot 5', 'slug' => 'foot-5', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Football', 'slug' => 'football', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Football Americain', 'slug' => 'football-americain', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Football en Salle', 'slug' => 'football-en-salle', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Golf', 'slug' => 'golf', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Handball', 'slug' => 'handball', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Hockey Subaquatique', 'slug' => 'hockey-subaquatique', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Hockey en Salle', 'slug' => 'hockey-en-salle', 'category_id' => 1]);

        Activite::factory()->create(['name' => 'Hockey sur Gazon', 'slug' => 'hockey-sur-gazon', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Hockey sur Glace', 'slug' => 'hockey-sur-glace', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Horse Ball', 'slug' => 'horse-ball', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Jorkyball', 'slug' => 'jorkyball', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Kinball', 'slug' => 'kinball', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Padel', 'slug' => 'padel', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Pelote Basque', 'slug' => 'pelote-basque', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Polo', 'slug' => 'polo', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Pétéca', 'slug' => 'peteca', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Rugby', 'slug' => 'rugby', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Sandball', 'slug' => 'sandball', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Softball', 'slug' => 'softball', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Squash', 'slug' => 'squash', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Tchoukball', 'slug' => 'tchoukball', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Tennis', 'slug' => 'tennis', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Tennis de Table', 'slug' => 'tennis-de-table', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Volley-ball', 'slug' => 'volley-ball', 'category_id' => 1]);
        Activite::factory()->create(['name' => 'Water-Polo', 'slug' => 'water-polo', 'category_id' => 1]);
        //combat
        Activite::factory()->create(['name' => 'Aikido', 'slug' => 'aikido', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Baton Defense', 'slug' => 'baton-defense', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Battodo', 'slug' => 'battodo', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Boxe Anglaise', 'slug' => 'boxe-anglaise', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Boxe Chinoise', 'slug' => 'boxe-chinoise', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Boxe Française', 'slug' => 'boxe-française', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Boxe Thailandaise', 'slug' => 'boxe-thailandaise', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Canne Defense', 'slug' => 'canne-defense', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Canne De Combat', 'slug' => 'canne-de-combat', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Capoera', 'slug' => 'capoera', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Catch', 'slug' => 'catch', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Chanbara', 'slug' => 'chanbara', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Escrime', 'slug' => 'escrime', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Free Fight', 'slug' => 'free-fight', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Full Contact', 'slug' => 'full-contact', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Gouren', 'slug' => 'gouren', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Grappling', 'slug' => 'grappling', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Hapkido', 'slug' => 'hapkido', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Iaido', 'slug' => 'iaido', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Jeet Kune Do', 'slug' => 'jeet-kune-do', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Jodo', 'slug' => 'jodo', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Ju-jitsu', 'slug' => 'ju-jitsu', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Ju-jitsu Brésilien', 'slug' => 'ju-jitsu-bresilien', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Judo', 'slug' => 'judo', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'K-One', 'slug' => 'k-one', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Kalarippayatt', 'slug' => 'kalarippayatt', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Kali Escrima', 'slug' => 'kali-escrima', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Kapap', 'slug' => 'kapap', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Karaté', 'slug' => 'karate', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Kendo', 'slug' => 'kendo', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Kenjutsu', 'slug' => 'kenjutsu', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Kobudo', 'slug' => 'kobudo', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Krav-Maga', 'slug' => 'krav-maga', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Kung-Fu', 'slug' => 'kung-fu', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Kyudo', 'slug' => 'kyudo', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Lutte', 'slug' => 'lutte', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Naginata', 'slug' => 'naginata', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Ninjutsu', 'slug' => 'ninjutsu', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Nunchaku', 'slug' => 'nunchaku', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Pa Kua', 'slug' => 'pa-kua', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Pancrate', 'slug' => 'pancrate', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Penchak-Silat', 'slug' => 'penchak-silat', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Qwan Ki Do', 'slug' => 'Qwan-ki-do', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'ROS', 'slug' => 'ros', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Sambo', 'slug' => 'sambo', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Sarbacana', 'slug' => 'sarbacana', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Self Défense', 'slug' => 'self-defense', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Shuai Jiao', 'slug' => 'shuai-jiao', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Sumo', 'slug' => 'sumo', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Systema', 'slug' => 'systema', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Taekwondo', 'slug' => 'taekwondo', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Tai-chi-chuan', 'slug' => 'tai-chi-chuan', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Tai-jitsu', 'slug' => 'tai-jitsu', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Thaing Bando', 'slug' => 'thaing-bando', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Viet Vo Dao', 'slug' => 'viet-vo-dao', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Wing Chun', 'slug' => 'wing-chun', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Yi-Quan', 'slug' => 'yi-quan', 'category_id' => 2]);
        Activite::factory()->create(['name' => 'Yoseikan Budo', 'slug' => 'yoseikan-budo', 'category_id' => 2]);

        //Montagne
        Activite::factory()->create(['name' => 'Accrobranche', 'slug' => 'accrobranche', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Agility', 'slug' => 'agility', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Air-Boat', 'slug' => 'air-boat', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Alpinisme', 'slug' => 'alpinisme', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Ball Trap', 'slug' => 'ball-trap', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Bobsleigh', 'slug' => 'bobsleigh', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Curling', 'slug' => 'curling', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Deltaplane', 'slug' => 'deltaplane', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Equitation', 'slug' => 'equitation', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Escalade', 'slug' => 'escalade', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Luge', 'slug' => 'luge', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Marche Nordique', 'slug' => 'marche-nordique', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Motoneige', 'slug' => 'motoneige', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Mountainboard', 'slug' => 'mountainboard', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Parachutisme', 'slug' => 'parachutisme', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Parapente', 'slug' => 'parapente', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Patin à Glace', 'slug' => 'patin-a-glace', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Patinage Artistique', 'slug' => 'patinage-artistique', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Patinage de Vitesse', 'slug' => 'patinage-de-vitesse', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Randonnée', 'slug' => 'randonnee', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Randonnée Equestre', 'slug' => 'randonnee-equestre', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Raquettes', 'slug' => 'raquettes', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Saut à l\'Elastique', 'slug' => 'saut-a-l-elastique', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Sauvetage', 'slug' => 'sauvetage', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Short Track', 'slug' => 'short-track', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Ski Alpin', 'slug' => 'ski-alpin', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Ski de Fond', 'slug' => 'ski-de-fond', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Ski de Vitesse', 'slug' => 'ski-de-vitesse', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Snowboard', 'slug' => 'snowboard', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Spéléologie', 'slug' => 'speleologie', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Tir Sportif', 'slug' => 'tir-sportif', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Tir à l\'Arc', 'slug' => 'tir-a-l-arc', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Traineau', 'slug' => 'traineau', 'category_id' => 3]);
        Activite::factory()->create(['name' => 'Via Ferrata', 'slug' => 'via-ferrata', 'category_id' => 3]);

        //Eau
        Activite::factory()->create(['name' => 'Apnée', 'slug' => 'apnee', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Aviron', 'slug' => 'aviron', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Bébés Nageurs', 'slug' => 'bebes-nageurs', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Canoé-kayak', 'slug' => 'canoe-kayak', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Canyoning', 'slug' => 'canyoning', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Char à Voile', 'slug' => 'char-a-voile', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Hydrospeed', 'slug' => 'hydrospeed', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Jet Ski', 'slug' => 'jet-ski', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Natation', 'slug' => 'natation', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Natation Synchronisée', 'slug' => 'natation-synchronisee', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Planche à Voile', 'slug' => 'planche-a-voile', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Plongeon', 'slug' => 'plongeon', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Plongée', 'slug' => 'plongee', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Pêche', 'slug' => 'peche', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Pêche Sous Marine', 'slug' => 'peche sous marine', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Rafting', 'slug' => 'rafting', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Ski Nautique', 'slug' => 'ski-nautique', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Snorkeling', 'slug' => 'snorkeling', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Surf', 'slug' => 'surf', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Voile', 'slug' => 'voile', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Wakeboard', 'slug' => 'wakeboard', 'category_id' => 4]);
        Activite::factory()->create(['name' => 'Windsurf', 'slug' => 'windsurf', 'category_id' => 4]);
        //fitness
        Activite::factory()->create(['name' => 'Aquabiking Vélo Aquatique', 'slug' => 'aquabiking-velo-aquatique', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Aquaboxing', 'slug' => 'aquaboxing', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Aquafitness', 'slug' => 'aquafitness', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Aquagym', 'slug' => 'aquagym', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Aquajogging', 'slug' => 'aquajogging', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Athlétisme', 'slug' => 'athletisme', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Aérobic Sportive', 'slug' => 'aerobic-sportive', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Cardiotraining', 'slug' => 'cardiotraining', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Cirque', 'slug' => 'cirque', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Course à pied', 'slug' => 'course-a-pied', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Dao Yin', 'slug' => 'dao-yin', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Fitness', 'slug' => 'fitness', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Gym Suédoise', 'slug' => 'gym-suedoise', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Gymnastique', 'slug' => 'gymnastique', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Gymnastique Rythmique', 'slug' => 'gymnastique-rythmique', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Musculation', 'slug' => 'musculation', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Pilates', 'slug' => 'pilates', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Qi Gong', 'slug' => 'qi-gong', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Yoga', 'slug' => 'yoga', 'category_id' => 5]);
        Activite::factory()->create(['name' => 'Zumba', 'slug' => 'zumba', 'category_id' => 5]);

        //mecanique
        Activite::factory()->create(['name' => 'Aviation', 'slug' => 'aviation', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'Aérostation', 'slug' => 'aerostation', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'BMX', 'slug' => 'bmx', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'Cyclisme sur Piste', 'slug' => 'cyclisme-sur-piste', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'Cyclisme sur Route', 'slug' => 'cyclisme-sur-route', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'Cyclocross', 'slug' => 'cyclocross', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'Cyclotourisme', 'slug' => 'cyclotourisme', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'Giraviation', 'slug' => 'giraviation', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'Karting', 'slug' => 'karting', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'Moto Vitesse', 'slug' => 'moto-vitesse', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'Motocross', 'slug' => 'motocross', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'Paramoteur', 'slug' => 'paramoteur', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'Quad', 'slug' => 'quad', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'Rallye', 'slug' => 'rallye', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'Rollers', 'slug' => 'rollers', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'Skate', 'slug' => 'skate', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'ULM', 'slug' => 'ulm', 'category_id' => 6]);
        Activite::factory()->create(['name' => 'VTT', 'slug' => 'vtt', 'category_id' => 6]);

        //Musique
        Activite::factory()->create(['name' => 'Accordéon', 'slug' => 'accordeon', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Alto', 'slug' => 'alto', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Banjo', 'slug' => 'banjo', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Basse', 'slug' => 'basse', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Batterie', 'slug' => 'batterie', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Chant', 'slug' => 'chant', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Chorale', 'slug' => 'chorale', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Clarinette', 'slug' => 'clarinette', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Clavecin', 'slug' => 'clavecin', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Contrebasse', 'slug' => 'contrebasse', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Cor', 'slug' => 'cor', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Cornemuse', 'slug' => 'cornemuse', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Djembé', 'slug' => 'djembe', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Flûte à Bec', 'slug' => 'flute-a-bec', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Guitare', 'slug' => 'guitare', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Guitare Electrique', 'slug' => 'guitare-electrique', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Harmonica', 'slug' => 'harmonica', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Harpe', 'slug' => 'harpe', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Hautbois', 'slug' => 'hautbois', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Orgues', 'slug' => 'orgues', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Piano', 'slug' => 'piano', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Platines', 'slug' => 'platines', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Saxophone', 'slug' => 'saxophone', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Trombone', 'slug' => 'trombone', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Trompette', 'slug' => 'trompette', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Tuba', 'slug' => 'tuba', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Violon', 'slug' => 'violon', 'category_id' => 7]);
        Activite::factory()->create(['name' => 'Violoncelle', 'slug' => 'violoncelle', 'category_id' => 7]);

        //Danse
        Activite::factory()->create(['name' => 'Cheerleading', 'slug' => 'cheerleading', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Claquettes', 'slug' => 'claquettes', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Country', 'slug' => 'country', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Danse Africaine', 'slug' => 'danse-africaine', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Danse Classique', 'slug' => 'danse-classique', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Danse Espagnole', 'slug' => 'danse-espagnole', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Danse Folklorique', 'slug' => 'danse-folklorique', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Danse Indienne', 'slug' => 'danse-indienne', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Danse Orientale', 'slug' => 'danse-orientale', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Danse Tahitienne', 'slug' => 'danse-tahitienne', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Danse de Salon', 'slug' => 'danse-de-salon', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Flamenco', 'slug' => 'flamenco', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Hip-Hop', 'slug' => 'hip-hop', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Modern Jazz', 'slug' => 'modern-jazz', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Pole Dance', 'slug' => 'pole-dance', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Rock', 'slug' => 'rock', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Salsa', 'slug' => 'salsa', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Samba', 'slug' => 'samba', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Swing', 'slug' => 'swing', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Tango', 'slug' => 'tango', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Tribal Fusion', 'slug' => 'tribal-fusion', 'category_id' => 8]);
        Activite::factory()->create(['name' => 'Valse', 'slug' => 'valse', 'category_id' => 8]);

        //loisirs-creatifs
        Activite::factory()->create(['name' => 'Dessin', 'slug' => 'dessin', 'category_id' => 9]);
        Activite::factory()->create(['name' => 'Ecriture', 'slug' => 'ecriture', 'category_id' => 9]);
        Activite::factory()->create(['name' => 'Graphisme', 'slug' => 'graphisme', 'category_id' => 9]);
        Activite::factory()->create(['name' => 'Peinture', 'slug' => 'peinture', 'category_id' => 9]);
        Activite::factory()->create(['name' => 'Photographie', 'slug' => 'photographie', 'category_id' => 9]);
        Activite::factory()->create(['name' => 'Poterie', 'slug' => 'poterie', 'category_id' => 9]);
        Activite::factory()->create(['name' => 'Sculpture', 'slug' => 'sculpture', 'category_id' => 9]);
        Activite::factory()->create(['name' => 'Theâtre', 'slug' => 'theatre', 'category_id' => 9]);
        Activite::factory()->create(['name' => 'Vidéo', 'slug' => 'video', 'category_id' => 9]);

        //jeux-divers
        Activite::factory()->create(['name' => 'Aéromodélisme', 'slug' => 'aeromodelisme', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Babyfoot', 'slug' => 'babyfoot', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Belotte', 'slug' => 'belotte', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Billards', 'slug' => 'billards', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Boomrang', 'slug' => 'boomrang', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Bowling', 'slug' => 'bowling', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Bridge', 'slug' => 'bridge', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Cartes', 'slug' => 'cartes', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Cerf-volant', 'slug' => 'cerf-volant', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Course d\'Orientation', 'slug' => 'course-d-orientation', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Cuisine', 'slug' => 'cuisine', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Echecs', 'slug' => 'echecs', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Escape Game', 'slug' => 'escape-game', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Fléchettes', 'slug' => 'flechettes', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Frisbee', 'slug' => 'frisbee', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Jeux Vidéos', 'slug' => 'jeux-videos', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Jeux de Roles', 'slug' => 'jeux-de-roles', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Jeux de Société', 'slug' => 'jeux-de-societe', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Laser Game', 'slug' => 'laser-game', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Littérature', 'slug' => 'litterature', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Magie', 'slug' => 'magie', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Massage', 'slug' => 'massage', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Minigolf', 'slug' => 'minigolf', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Modélisme', 'slug' => 'modelisme', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Paintball', 'slug' => 'paintball', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Philatélie', 'slug' => 'philatelie', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Poker', 'slug' => 'poker', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Pédalo', 'slug' => 'pedalo', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Pétanque', 'slug' => 'petanque', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Rire', 'slug' => 'rire', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Saunas Hamams', 'slug' => 'saunas-hamams', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Scrabble', 'slug' => 'scrabble', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Tarot', 'slug' => 'tarot', 'category_id' => 10]);
        Activite::factory()->create(['name' => 'Thalassothérapie', 'slug' => 'thalassotherapie', 'category_id' => 10]);

        $sports= Activite::all();
    }
}
