<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Categorie;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'      => 'Administrateur',
            'email'     => 'admin@bibliotheque.fr',
            'telephone' => '0600000000',
            'role'      => 'admin',
            'password'  => Hash::make(env('ADMIN_PASSWORD', 'ChangeMoi@2026!')),
        ]);

        $categories = [
            ['nom' => 'Roman',           'description' => 'Fiction narrative longue'],
            ['nom' => 'Science-fiction', 'description' => "Littérature d'anticipation scientifique"],
            ['nom' => 'Histoire',        'description' => 'Ouvrages historiques et biographies'],
            ['nom' => 'Sciences',        'description' => 'Mathématiques, physique, biologie...'],
            ['nom' => 'Informatique',    'description' => 'Programmation, réseaux, bases de données'],
            ['nom' => 'Philosophie',     'description' => 'Pensée, éthique, logique'],
            ['nom' => 'Jeunesse',        'description' => 'Livres pour enfants et adolescents'],
            ['nom' => 'Manga',           'description' => 'Bandes dessinées japonaises'],
        ];

        foreach ($categories as $cat) {
            Categorie::create($cat);
        }

        $this->call(LivreSeeder::class);
    }
}
