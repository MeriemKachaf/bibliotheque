<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// MODÈLE Emprunt — représente un emprunt (qui a pris quel livre et quand)
class Emprunt extends Model
{
    // Colonnes qu'on peut remplir via un formulaire
    protected $fillable = [
        'user_id', 'livre_id', 'date_emprunt',
        'date_retour_prevue', 'date_retour_effective', 'statut',
    ];

    // Conversion automatique des dates en objets Carbon (facilite le calcul de dates)
    protected function casts(): array
    {
        return [
            'date_emprunt'          => 'date',
            'date_retour_prevue'    => 'date',
            'date_retour_effective' => 'date',
        ];
    }

    // Relation : un emprunt appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation : un emprunt concerne un livre
    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }

    // Retourne le label lisible du statut (ex: "en_cours" → "En cours")
    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'en_cours' => 'En cours',
            'rendu'    => 'Rendu',
            'en_retard'=> 'En retard',
            default    => $this->statut,
        };
    }

    // Retourne la couleur Bootstrap selon le statut (pour afficher un badge coloré)
    public function getStatutBadgeAttribute(): string
    {
        return match($this->statut) {
            'en_cours' => 'warning',
            'rendu'    => 'success',
            'en_retard'=> 'danger',
            default    => 'secondary',
        };
    }
}
