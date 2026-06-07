<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Livre;
use App\Models\Categorie;

class LivreSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Livre::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $cats = Categorie::pluck('id', 'nom');

        $livres = [
            // Romans
            ['titre' => 'Les Cerfs-volants de Kaboul',       'auteur' => 'Khaled Hosseini',          'editeur' => 'Belfond',        'annee' => 2003, 'qte' => 4, 'cat' => 'Roman',          'isbn' => '9782714440334', 'description' => "Dans l'Afghanistan des années 1970, l'amitié entre Amir et Hassan est brisée par une trahison. Un récit bouleversant sur la culpabilité et la rédemption."],
            ['titre' => "L'Alchimiste",                      'auteur' => 'Paulo Coelho',              'editeur' => 'Anne Carrière',  'annee' => 1988, 'qte' => 5, 'cat' => 'Roman',          'isbn' => '9782843370151', 'description' => "Un jeune berger andalou part à la recherche d'un trésor en Égypte. Un roman philosophique sur l'accomplissement de sa légende personnelle."],
            ['titre' => 'Hunger Games',                      'auteur' => 'Suzanne Collins',           'editeur' => 'Pocket',         'annee' => 2008, 'qte' => 6, 'cat' => 'Roman',          'isbn' => '9782266181273', 'description' => "Dans un futur dystopique, des adolescents sont forcés de s'affronter dans un jeu télévisé mortel. Katniss Everdeen refuse d'être une simple pion du système."],
            ['titre' => 'Da Vinci Code',                     'auteur' => 'Dan Brown',                 'editeur' => 'JC Lattès',      'annee' => 2003, 'qte' => 3, 'cat' => 'Roman',          'isbn' => '9782709624930', 'description' => "Un meurtre au Louvre plonge un symbologiste dans une course-poursuite à travers l'Europe pour percer les secrets d'une société secrète."],
            ['titre' => 'Gone Girl',                         'auteur' => 'Gillian Flynn',             'editeur' => 'Sonatine',       'annee' => 2012, 'qte' => 4, 'cat' => 'Roman',          'isbn' => '9782355841965', 'description' => "Le matin de leur cinquième anniversaire de mariage, Amy Dunne disparaît. Son mari devient le principal suspect dans une affaire qui cache bien des mensonges."],
            ['titre' => 'La Vague',                          'auteur' => 'Todd Strasser',             'editeur' => 'Pocket',         'annee' => 1981, 'qte' => 3, 'cat' => 'Roman',          'isbn' => '9782266126113', 'description' => "Une expérience sociale dans un lycée américain dérape quand un professeur crée un mouvement totalitaire pour expliquer l'adhésion au nazisme."],
            // Science-fiction
            ['titre' => 'Le Meilleur des mondes',            'auteur' => 'Aldous Huxley',             'editeur' => 'Pocket',         'annee' => 1932, 'qte' => 4, 'cat' => 'Science-fiction', 'isbn' => '9782266128278', 'description' => "Dans un monde où les êtres humains sont fabriqués en série et conditionnés au bonheur, un sauvage découvre le prix réel de cette utopie."],
            ['titre' => 'Seul sur Mars',                     'auteur' => 'Andy Weir',                 'editeur' => 'Michel Lafon',   'annee' => 2011, 'qte' => 5, 'cat' => 'Science-fiction', 'isbn' => '9782749926209', 'description' => "Abandonné sur Mars après une tempête, un astronaute doit survivre avec des ressources limitées en attendant les secours. Un thriller scientifique haletant."],
            ['titre' => 'Ready Player One',                  'auteur' => 'Ernest Cline',              'editeur' => 'Pocket',         'annee' => 2011, 'qte' => 5, 'cat' => 'Science-fiction', 'isbn' => '9782266244596', 'description' => "En 2044, l'humanité s'échappe dans l'OASIS, un univers virtuel. Un adolescent part à la chasse au trésor numérique pour en contrôler les clés."],
            ['titre' => 'La Stratégie Ender',                'auteur' => 'Orson Scott Card',          'editeur' => "J'ai lu",        'annee' => 1985, 'qte' => 3, 'cat' => 'Science-fiction', 'isbn' => '9782290340448', 'description' => "Un enfant surdoué est recruté pour une école militaire spatiale afin de préparer l'humanité à une guerre contre une civilisation extraterrestre."],
            // Histoire
            ['titre' => "Le Monde d'hier",                   'auteur' => 'Stefan Zweig',              'editeur' => 'Belfond',        'annee' => 1942, 'qte' => 3, 'cat' => 'Histoire',        'isbn' => '9782714440358', 'description' => "Les mémoires de Zweig, portrait d'une Europe cultivée et humaniste détruite par les totalitarismes. Écrit juste avant son suicide en exil."],
            ['titre' => 'La Nuit',                           'auteur' => 'Elie Wiesel',               'editeur' => 'Minuit',         'annee' => 1958, 'qte' => 4, 'cat' => 'Histoire',        'isbn' => '9782707302526', 'description' => "Témoignage autobiographique d'un survivant des camps de concentration nazis. Un récit sobre et dévastateur sur la déshumanisation."],
            ['titre' => 'Le Temps des révolutions',          'auteur' => 'Jean-Paul Bertaud',         'editeur' => 'Fayard',         'annee' => 2004, 'qte' => 2, 'cat' => 'Histoire',        'isbn' => '9782213618357', 'description' => "Une analyse complète des révolutions françaises et américaines et de leur impact durable sur le monde moderne."],
            // Sciences
            ['titre' => 'Une brève histoire du temps',       'auteur' => 'Stephen Hawking',           'editeur' => 'Flammarion',     'annee' => 1988, 'qte' => 4, 'cat' => 'Sciences',        'isbn' => '9782081379046', 'description' => "Des trous noirs au big bang, Hawking explique les grandes théories de l'univers de manière accessible au grand public."],
            ['titre' => 'Le Gène égoïste',                   'auteur' => 'Richard Dawkins',           'editeur' => 'Odile Jacob',    'annee' => 1976, 'qte' => 2, 'cat' => 'Sciences',        'isbn' => '9782738124456', 'description' => "Dawkins propose une vision de l'évolution centrée sur le gène plutôt que sur l'individu ou l'espèce, révolutionnant la biologie évolutive."],
            ['titre' => 'Cosmos',                            'auteur' => 'Carl Sagan',                'editeur' => 'Pocket',         'annee' => 1980, 'qte' => 3, 'cat' => 'Sciences',        'isbn' => '9782266096096', 'description' => "Un voyage poétique et scientifique à travers l'univers, explorant l'origine de la vie, des étoiles et du cosmos tout entier."],
            // Informatique
            ['titre' => 'Python Crash Course',               'auteur' => 'Eric Matthes',              'editeur' => 'No Starch Press','annee' => 2015, 'qte' => 5, 'cat' => 'Informatique',   'isbn' => '9781593276034', 'description' => "Une introduction complète à Python pour les débutants, avec des projets concrets : jeux, visualisations de données et applications web."],
            ['titre' => 'The Pragmatic Programmer',          'auteur' => 'David Thomas',              'editeur' => 'Addison-Wesley', 'annee' => 1999, 'qte' => 3, 'cat' => 'Informatique',   'isbn' => '9780135957059', 'description' => "Un guide incontournable sur les bonnes pratiques de développement logiciel, de la conception à la livraison, toujours d'actualité."],
            ['titre' => 'Introduction aux algorithmes',      'auteur' => 'Thomas H. Cormen',          'editeur' => 'MIT Press',      'annee' => 2001, 'qte' => 2, 'cat' => 'Informatique',   'isbn' => '9780262033848', 'description' => "La référence mondiale pour l'étude des algorithmes et des structures de données, utilisée dans les universités du monde entier."],
            // Philosophie
            ['titre' => 'Le Mythe de Sisyphe',               'auteur' => 'Albert Camus',              'editeur' => 'Gallimard',      'annee' => 1942, 'qte' => 4, 'cat' => 'Philosophie',    'isbn' => '9782070360284', 'description' => "Camus interroge le sens de la vie face à l'absurde. Son célèbre essai conclut qu'il faut imaginer Sisyphe heureux."],
            ['titre' => 'Ainsi parlait Zarathoustra',        'auteur' => 'Friedrich Nietzsche',       'editeur' => 'Gallimard',      'annee' => 1885, 'qte' => 3, 'cat' => 'Philosophie',    'isbn' => '9782070412822', 'description' => "Le prophète Zarathoustra descend de sa montagne pour enseigner le concept du Surhomme et l'éternel retour dans ce texte fondateur."],
            ['titre' => "L'Art d'avoir toujours raison",     'auteur' => 'Arthur Schopenhauer',       'editeur' => 'Mille et une nuits', 'annee' => 1831, 'qte' => 5, 'cat' => 'Philosophie', 'isbn' => '9782842059200', 'description' => "Schopenhauer décrit 38 stratagèmes rhétoriques pour gagner un débat, même quand on a tort. Un traité cynique et brillant sur l'argumentation."],
            // Jeunesse
            ['titre' => 'Percy Jackson : Le Voleur de foudre','auteur' => 'Rick Riordan',             'editeur' => 'Albin Michel',   'annee' => 2005, 'qte' => 7, 'cat' => 'Jeunesse',       'isbn' => '9782226179678', 'description' => "Percy Jackson découvre qu'il est le fils de Poséidon. Il part en quête avec ses amis pour récupérer l'éclair de Zeus et éviter une guerre entre dieux."],
            ['titre' => "Journal d'un Dégonflé",             'auteur' => 'Jeff Kinney',               'editeur' => 'Auzou',          'annee' => 2007, 'qte' => 6, 'cat' => 'Jeunesse',       'isbn' => '9782733803271', 'description' => "Greg Heffley traverse les affres du collège avec humour. Un roman illustré hilarant qui parle de popularité, d'amitié et de galères adolescentes."],
            ['titre' => 'Divergente',                        'auteur' => 'Veronica Roth',             'editeur' => 'Nathan',         'annee' => 2011, 'qte' => 5, 'cat' => 'Jeunesse',       'isbn' => '9782092531365', 'description' => "Dans un Chicago post-apocalyptique divisé en factions, Tris découvre qu'elle est Divergente — et que cela fait d'elle une cible mortelle."],
            // Manga
            ['titre' => 'One Piece T1',                      'auteur' => 'Eiichiro Oda',              'editeur' => 'Glénat',         'annee' => 1997, 'qte' => 8, 'cat' => 'Manga',          'isbn' => '9782723428262', 'description' => "Monkey D. Luffy, dont le corps est élastique après avoir mangé un fruit du démon, rêve de devenir le Roi des Pirates et part chercher le légendaire One Piece."],
            ['titre' => "L'Attaque des Titans T1",           'auteur' => 'Hajime Isayama',            'editeur' => 'Pika',           'annee' => 2009, 'qte' => 6, 'cat' => 'Manga',          'isbn' => '9782811604493', 'description' => "L'humanité vit retranchée derrière d'immenses murs pour se protéger des Titans. Eren Jäger jure de les exterminer après la destruction de sa ville."],
            ['titre' => 'Demon Slayer T1',                   'auteur' => 'Koyoharu Gotouge',          'editeur' => 'Panini Manga',   'annee' => 2016, 'qte' => 7, 'cat' => 'Manga',          'isbn' => '9791032703069', 'description' => "Tanjiro Kamado devient chasseur de démons pour sauver sa sœur transformée en démon et venger sa famille massacrée."],
            ['titre' => 'Death Note T1',                     'auteur' => 'Tsugumi Ohba',              'editeur' => 'Kana',           'annee' => 2003, 'qte' => 5, 'cat' => 'Manga',          'isbn' => '9782871294924', 'description' => "Light Yagami trouve un cahier surnaturel permettant de tuer n'importe qui en écrivant son nom. Un thriller psychologique sur le bien, le mal et la justice."],
        ];

        foreach ($livres as $data) {
            Livre::create([
                'titre'            => $data['titre'],
                'auteur'           => $data['auteur'],
                'editeur'          => $data['editeur'],
                'annee_publication'=> $data['annee'],
                'quantite'         => $data['qte'],
                'categorie_id'     => $cats[$data['cat']] ?? null,
                'isbn'             => $data['isbn'] ?? null,
                'description'      => $data['description'] ?? null,
            ]);
        }
    }
}
