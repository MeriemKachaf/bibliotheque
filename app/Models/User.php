<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// MODÈLE User — représente un utilisateur dans la base de données
class User extends Authenticatable
{
    use Notifiable;

    // Colonnes qu'on peut remplir via un formulaire
    protected $fillable = ['name', 'email', 'telephone', 'role', 'password'];

    // Colonnes jamais affichées (sécurité : le mot de passe ne sort jamais en JSON)
    protected $hidden = ['password', 'remember_token'];

    // Conversions automatiques : le mot de passe est toujours hashé, la date est formatée
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // Vérifie si l'utilisateur est admin (retourne vrai ou faux)
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Relation : un utilisateur peut avoir plusieurs emprunts
    public function emprunts()
    {
        return $this->hasMany(Emprunt::class);
    }
}
