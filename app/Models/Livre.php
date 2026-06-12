<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// MODÈLE Livre — représente un livre dans la base de données
class Livre extends Model
{
    // Colonnes qu'on peut remplir via un formulaire
    protected $fillable = [
        'titre', 'auteur', 'isbn', 'editeur', 'annee_publication',
        'description', 'quantite', 'categorie_id', 'photo',
    ];

    // Relation : un livre appartient à une catégorie
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    // Relation : un livre peut avoir plusieurs emprunts
    public function emprunts()
    {
        return $this->hasMany(Emprunt::class);
    }

    // Vérifie si le livre est disponible (quantité > 0)
    public function isDisponible(): bool
    {
        return $this->quantite > 0;
    }

    // Génère l'URL de la photo — si pas de photo, retourne une image par défaut
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('images/livres/' . $this->photo);
        }
        return asset('images/livres/livre-placeholder.png');
    }
}
