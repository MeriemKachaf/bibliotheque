<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livre extends Model
{
    protected $fillable = [
        'titre', 'auteur', 'isbn', 'editeur', 'annee_publication',
        'description', 'quantite', 'categorie_id', 'photo',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function emprunts()
    {
        return $this->hasMany(Emprunt::class);
    }

    public function isDisponible(): bool
    {
        return $this->quantite > 0;
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('images/livres/' . $this->photo);
        }
        return asset('images/livres/livre-placeholder.png');
    }
}
