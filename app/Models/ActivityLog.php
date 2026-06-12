<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// MODÈLE ActivityLog — enregistre toutes les actions importantes (RGPD)
class ActivityLog extends Model
{
    // Colonnes qu'on peut remplir
    protected $fillable = ['user_id', 'action', 'description', 'ip', 'niveau'];

    // Relation : chaque log appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Méthode statique pour enregistrer une action facilement depuis n'importe où
    // Exemple d'utilisation : ActivityLog::record('connexion', 'Connexion réussie')
    public static function record(string $action, string $description, string $niveau = 'info'): void
    {
        static::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'description' => $description,
            'ip'          => request()->ip(), // adresse IP de l'utilisateur
            'niveau'      => $niveau,         // info, warning ou error
        ]);
    }
}
