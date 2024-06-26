<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListeFamillesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('liste_familles')->delete();

        DB::table('liste_familles')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'Balle',
                'slug' => 'balle',
                'nom_long' => 'Sports de balle',
                'title' => 'Sports de balle et collectif.loc.',
                'title_clubs' => 'Sports de balle et collectif.loc.',
                'title_profs' => 'Sports de balle et collectif.loc.',
                'meta_description' => 'Vous souhaitez pratiquer un sport de balle.loc. ? .nb_clubs. clubs sur notre site prêts à vous accueillir.',
                'meta_description_clubs' => 'Vous souhaitez pratiquer un sport de balle.loc. ? .nb_clubs. clubs sur notre site prêts à vous accueillir.',
                'meta_description_profs' => 'Vous souhaitez pratiquer un sport de balle.loc. ? .nb_clubs. clubs sur notre site prêts à vous accueillir.',
                'h1' => 'Clubs de sport de balle.loc.',
                'h1_clubs' => 'Clubs de sport de balle.loc.',
                'h1_profs' => 'Clubs de sport de balle.loc.',
                'h2' => 'Trouvez un club de sport de balle .loc.',
                'h2_clubs' => 'Trouvez un club de sport de balle .loc.',
                'h2_profs' => 'Trouvez un club de sport de balle .loc.',
                'description' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer un sport de balle n\'a jamais été aussi simple !',
                'description_clubs' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer un sport de balle n\'a jamais été aussi simple !',
                'description_profs' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer un sport de balle n\'a jamais été aussi simple !',
                'type' => 'Sport',
                'ordre' => 0,
                'view_count' => null,
                'created_at' => '2023-04-26 17:36:59',
                'updated_at' => '2023-09-05 16:00:00',
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'Combat',
                'slug' => 'combat',
                'nom_long' => 'Sports de combat',
                'title' => 'Sports de combat et art martiaux.loc.',
                'title_clubs' => 'Sports de combat et art martiaux.loc.',
                'title_profs' => 'Sports de combat et art martiaux.loc.',
                'meta_description' => 'Vous souhaitez pratiquer un sport de combat.loc. ? .nb_clubs. clubs sur notre site prêts à vous accueillir.',
                'meta_description_clubs' => 'Vous souhaitez pratiquer un sport de combat.loc. ? .nb_clubs. clubs sur notre site prêts à vous accueillir.',
                'meta_description_profs' => 'Vous souhaitez pratiquer un sport de combat.loc. ? .nb_clubs. clubs sur notre site prêts à vous accueillir.',
                'h1' => 'Clubs de sport de combat.loc.',
                'h1_clubs' => 'Clubs de sport de combat.loc.',
                'h1_profs' => 'Clubs de sport de combat.loc.',
                'h2' => 'Trouvez un club de sport de combat .loc.',
                'h2_clubs' => 'Trouvez un club de sport de combat .loc.',
                'h2_profs' => 'Trouvez un club de sport de combat .loc.',
                'description' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer un sport de combat n\'a jamais été aussi simple !',
                'description_clubs' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer un sport de combat n\'a jamais été aussi simple !',
                'description_profs' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer un sport de combat n\'a jamais été aussi simple !',
                'type' => 'Sport',
                'ordre' => 0,
                'view_count' => null,
                'created_at' => '2023-04-26 17:36:59',
                'updated_at' => '2023-09-05 16:00:00',
            ),
            2 =>
            array(
                'id' => 3,
                'name' => 'Montagne',
                'slug' => 'montagne',
                'nom_long' => 'Sports de montagne',
                'title' => 'Sports de montagne et nature.loc.',
                'title_clubs' => 'Sports de montagne et nature.loc.',
                'title_profs' => 'Sports de montagne et nature.loc.',
                'meta_description' => 'Vous souhaitez pratiquer un sport montagne et nature.loc. ? .nb_clubs. clubs sur notre site prêts à vous accueillir.',
                'meta_description_clubs' => 'Vous souhaitez pratiquer un sport montagne et nature.loc. ? .nb_clubs. clubs sur notre site prêts à vous accueillir.',
                'meta_description_profs' => 'Vous souhaitez pratiquer un sport montagne et nature.loc. ? .nb_clubs. clubs sur notre site prêts à vous accueillir.',
                'h1' => 'Clubs de sport de montagne.loc.',
                'h1_clubs' => 'Clubs de sport de montagne.loc.',
                'h1_profs' => 'Clubs de sport de montagne.loc.',
                'h2' => 'Trouvez un club de sport de montagne .loc.',
                'h2_clubs' => 'Trouvez un club de sport de montagne .loc.',
                'h2_profs' => 'Trouvez un club de sport de montagne .loc.',
                'description' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer un sport de montagne/ nature n\'a jamais été aussi simple !',
                'description_clubs' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer un sport de montagne/ nature n\'a jamais été aussi simple !',
                'description_profs' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer un sport de montagne/ nature n\'a jamais été aussi simple !',
                'type' => 'Sport',
                'ordre' => 0,
                'view_count' => null,
                'created_at' => '2023-04-26 17:36:59',
                'updated_at' => '2023-09-05 16:00:00',
            ),
            3 =>
            array(
                'id' => 4,
                'name' => 'Eau',
                'slug' => 'eau',
                'nom_long' => 'Sports d\'eau',
                'title' => 'Sports aquatiques.loc.',
                'title_clubs' => 'Sports aquatiques.loc.',
                'title_profs' => 'Sports aquatiques.loc.',
                'meta_description' => 'Vous souhaitez pratiquer un sport aquatique.loc. ? .nb_clubs. clubs sur notre site prêts à vous accueillir.',
                'meta_description_clubs' => 'Vous souhaitez pratiquer un sport aquatique.loc. ? .nb_clubs. clubs sur notre site prêts à vous accueillir.',
                'meta_description_profs' => 'Vous souhaitez pratiquer un sport aquatique.loc. ? .nb_clubs. clubs sur notre site prêts à vous accueillir.',
                'h1' => 'Clubs de sport de d\'eau.loc.',
                'h1_clubs' => 'Clubs de sport de d\'eau.loc.',
                'h1_profs' => 'Clubs de sport de d\'eau.loc.',
                'h2' => 'Trouvez un club de sport d\'eau.loc.',
                'h2_clubs' => 'Trouvez un club de sport d\'eau.loc.',
                'h2_profs' => 'Trouvez un club de sport d\'eau.loc.',
                'description' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer une activité aquatique n\'a jamais été aussi simple !',
                'description_clubs' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer une activité aquatique n\'a jamais été aussi simple !',
                'description_profs' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer une activité aquatique n\'a jamais été aussi simple !',
                'type' => 'Sport',
                'ordre' => 0,
                'view_count' => null,
                'created_at' => '2023-04-26 17:36:59',
                'updated_at' => '2023-09-05 16:00:00',
            ),
            4 =>
            array(
                'id' => 5,
                'name' => 'Fitness',
                'slug' => 'fitness',
                'nom_long' => 'Fitness et musculation',
                'title' => 'Fitness et musculation.loc.',
                'title_clubs' => 'Fitness et musculation.loc.',
                'title_profs' => 'Fitness et musculation.loc.',
                'meta_description' => 'Besoin de vous refaire une santé  ? .nb_clubs. clubs de remise en forme.loc.  disponibles sur notre site',
                'meta_description_clubs' => 'Besoin de vous refaire une santé  ? .nb_clubs. clubs de remise en forme.loc.  disponibles sur notre site',
                'meta_description_profs' => 'Besoin de vous refaire une santé  ? .nb_clubs. clubs de remise en forme.loc.  disponibles sur notre site',
                'h1' => 'Clubs de fitness et musculation.loc.',
                'h1_clubs' => 'Clubs de fitness et musculation.loc.',
                'h1_profs' => 'Clubs de fitness et musculation.loc.',
                'h2' => 'Trouvez un club fitness.loc.',
                'h2_clubs' => 'Trouvez un club fitness.loc.',
                'h2_profs' => 'Trouvez un club fitness.loc.',
                'description' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Entretenir son corps n\'a jamais été aussi simple !',
                'description_clubs' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Entretenir son corps n\'a jamais été aussi simple !',
                'description_profs' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Entretenir son corps n\'a jamais été aussi simple !',
                'type' => 'Sport',
                'ordre' => 0,
                'view_count' => null,
                'created_at' => '2023-04-26 17:36:59',
                'updated_at' => '2023-09-05 16:00:00',
            ),
            5 =>
            array(
                'id' => 6,
                'name' => 'Mécanique',
                'slug' => 'mecanique',
                'nom_long' => 'Sports mécaniques',
                'title' => 'Sports mécaniques.loc.',
                'title_clubs' => 'Sports mécaniques.loc.',
                'title_profs' => 'Sports mécaniques.loc.',
                'meta_description' => 'Envie de sensations fortes ? Faites votre choix parmi .nb_clubs. clubs de sport mécaniques prêts à vous accueillir.',
                'meta_description_clubs' => 'Envie de sensations fortes ? Faites votre choix parmi .nb_clubs. clubs de sport mécaniques prêts à vous accueillir.',
                'meta_description_profs' => 'Envie de sensations fortes ? Faites votre choix parmi .nb_clubs. clubs de sport mécaniques prêts à vous accueillir.',
                'h1' => 'Clubs de sport mécaniques.loc.',
                'h1_clubs' => 'Clubs de sport mécaniques.loc.',
                'h1_profs' => 'Clubs de sport mécaniques.loc.',
                'h2' => 'Trouvez un club de sport mécanique.loc.',
                'h2_clubs' => 'Trouvez un club de sport mécanique.loc.',
                'h2_profs' => 'Trouvez un club de sport mécanique.loc.',
                'description' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer une activité mécanique n\'a jamais été aussi simple !',
                'description_clubs' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer une activité mécanique n\'a jamais été aussi simple !',
                'description_profs' => 'Consultez la liste des clubs disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer une activité mécanique n\'a jamais été aussi simple !',
                'type' => 'Sport',
                'ordre' => 0,
                'view_count' => null,
                'created_at' => '2023-04-26 17:36:59',
                'updated_at' => '2023-09-05 16:00:00',
            ),
            6 =>
            array(
                'id' => 7,
                'name' => 'Musique',
                'slug' => 'musique',
                'nom_long' => 'Musique',
                'title' => 'Cours de musique.loc.',
                'title_clubs' => 'Cours de musique.loc.',
                'title_profs' => 'Cours de musique.loc.',
                'meta_description' => 'Envie d\'apprendre un instrument ? Plus de .nb_clubs. cours de musique disponibles sur notre site.',
                'meta_description_clubs' => 'Envie d\'apprendre un instrument ? Plus de .nb_clubs. cours de musique disponibles sur notre site.',
                'meta_description_profs' => 'Envie d\'apprendre un instrument ? Plus de .nb_clubs. cours de musique disponibles sur notre site.',
                'h1' => 'Cours de musique.loc.',
                'h1_clubs' => 'Cours de musique.loc.',
                'h1_profs' => 'Cours de musique.loc.',
                'h2' => 'Trouvez un cours de musique.loc.',
                'h2_clubs' => 'Trouvez un cours de musique.loc.',
                'h2_profs' => 'Trouvez un cours de musique.loc.',
                'description' => 'Consultez la liste des cours disponibles, comparez services, tarifs et horaires en 2 clics ! Apprendre un instrument de musique n\'a jamais été aussi simple !',
                'description_clubs' => 'Consultez la liste des cours disponibles, comparez services, tarifs et horaires en 2 clics ! Apprendre un instrument de musique n\'a jamais été aussi simple !',
                'description_profs' => 'Consultez la liste des cours disponibles, comparez services, tarifs et horaires en 2 clics ! Apprendre un instrument de musique n\'a jamais été aussi simple !',
                'type' => 'Loisirs',
                'ordre' => 0,
                'view_count' => null,
                'created_at' => '2023-04-26 17:36:59',
                'updated_at' => '2023-09-05 16:00:00',
            ),
            7 =>
            array(
                'id' => 8,
                'name' => 'Danse',
                'slug' => 'danse',
                'nom_long' => 'Danse',
                'title' => 'Cours de danse.loc.',
                'title_clubs' => 'Cours de danse.loc.',
                'title_profs' => 'Cours de danse.loc.',
                'meta_description' => 'Vous souhaitez apprendre la danse .loc. ? .nb_clubs. cours disponibles dès maintenant.',
                'meta_description_clubs' => 'Vous souhaitez apprendre la danse .loc. ? .nb_clubs. cours disponibles dès maintenant.',
                'meta_description_profs' => 'Vous souhaitez apprendre la danse .loc. ? .nb_clubs. cours disponibles dès maintenant.',
                'h1' => 'Cours de danse.loc.',
                'h1_clubs' => 'Cours de danse.loc.',
                'h1_profs' => 'Cours de danse.loc.',
                'h2' => 'Trouvez un cours de danse.loc.',
                'h2_clubs' => 'Trouvez un cours de danse.loc.',
                'h2_profs' => 'Trouvez un cours de danse.loc.',
                'description' => 'Consultez la liste des cours disponibles, comparez services, tarifs et horaires en 2 clics ! Apprendre à danser n\'a jamais été aussi simple !',
                'description_clubs' => 'Consultez la liste des cours disponibles, comparez services, tarifs et horaires en 2 clics ! Apprendre à danser n\'a jamais été aussi simple !',
                'description_profs' => 'Consultez la liste des cours disponibles, comparez services, tarifs et horaires en 2 clics ! Apprendre à danser n\'a jamais été aussi simple !',
                'type' => 'Loisirs',
                'ordre' => 0,
                'view_count' => null,
                'created_at' => '2023-04-26 17:36:59',
                'updated_at' => '2023-09-05 16:00:00',
            ),
            8 =>
            array(
                'id' => 9,
                'name' => 'Loisirs créatifs',
                'slug' => 'creations',
                'nom_long' => 'Loisirs créatifs',
                'title' => 'Loisirs créatifs.loc.',
                'title_clubs' => 'Loisirs créatifs.loc.',
                'title_profs' => 'Loisirs créatifs.loc.',
                'meta_description' => 'Envie d\'apprendre le théâtre, la photo, le dessin ? Découvrez nos cours de loisirs créatifs.loc.',
                'meta_description_clubs' => 'Envie d\'apprendre le théâtre, la photo, le dessin ? Découvrez nos cours de loisirs créatifs.loc.',
                'meta_description_profs' => 'Envie d\'apprendre le théâtre, la photo, le dessin ? Découvrez nos cours de loisirs créatifs.loc.',
                'h1' => 'Cours et clubs de loisirs créatifs.loc.',
                'h1_clubs' => 'Cours et clubs de loisirs créatifs.loc.',
                'h1_profs' => 'Cours et clubs de loisirs créatifs.loc.',
                'h2' => 'Pratiquez un loisirs créatif.loc.',
                'h2_clubs' => 'Pratiquez un loisirs créatif.loc.',
                'h2_profs' => 'Pratiquez un loisirs créatif.loc.',
                'description' => 'Consultez la liste des activités disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer un loisir créatif n\'a jamais été aussi simple !',
                'description_clubs' => 'Consultez la liste des activités disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer un loisir créatif n\'a jamais été aussi simple !',
                'description_profs' => 'Consultez la liste des activités disponibles, comparez services, tarifs et horaires en 2 clics ! Pratiquer un loisir créatif n\'a jamais été aussi simple !',
                'type' => 'Loisirs',
                'ordre' => 0,
                'view_count' => null,
                'created_at' => '2023-04-26 17:36:59',
                'updated_at' => '2023-09-05 16:00:00',
            ),
            9 =>
            array(
                'id' => 10,
                'name' => 'Jeux - divers',
                'slug' => 'jeux-divers',
                'nom_long' => 'Jeux et divertissements',
                'title' => 'Jeux et divertissement.loc.',
                'title_clubs' => 'Jeux et divertissement.loc.',
                'title_profs' => 'Jeux et divertissement.loc.',
                'meta_description' => 'Besoin de vous détendre.loc. ? Plus de .nb_clubs. structures de jeux et de divertissement disponibles.',
                'meta_description_clubs' => 'Besoin de vous détendre.loc. ? Plus de .nb_clubs. structures de jeux et de divertissement disponibles.',
                'meta_description_profs' => 'Besoin de vous détendre.loc. ? Plus de .nb_clubs. structures de jeux et de divertissement disponibles.',
                'h1' => 'Jeux et  divertissements.loc.',
                'h1_clubs' => 'Jeux et  divertissements.loc.',
                'h1_profs' => 'Jeux et  divertissements.loc.',
                'h2' => 'Trouvez un jeux ou divertissement.loc.',
                'h2_clubs' => 'Trouvez un jeux ou divertissement.loc.',
                'h2_profs' => 'Trouvez un jeux ou divertissement.loc.',
                'description' => 'Consultez la liste des jeux et divertissements disponibles, comparez services, tarifs et horaires en 2 clics ! S\'amuser n\'a jamais été aussi simple !',
                'description_clubs' => 'Consultez la liste des jeux et divertissements disponibles, comparez services, tarifs et horaires en 2 clics ! S\'amuser n\'a jamais été aussi simple !',
                'description_profs' => 'Consultez la liste des jeux et divertissements disponibles, comparez services, tarifs et horaires en 2 clics ! S\'amuser n\'a jamais été aussi simple !',
                'type' => 'Loisirs',
                'ordre' => 0,
                'view_count' => null,
                'created_at' => '2023-04-26 17:36:59',
                'updated_at' => '2023-09-05 16:00:00',
            ),
        ));


    }
}
