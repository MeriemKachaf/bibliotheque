<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emprunt extends Model
{
    protected $fillable = [
        'user_id', 'livre_id', 'date_emprunt',
        'date_retour_prevue', 'date_retour_effective', 'statut',
    ];

    protected function casts(): array
    {
        return [
            'date_emprunt'          => 'date',
            'date_retour_prevue'    => 'date',
            'date_retour_effective' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }

    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'en_cours' => 'En cours',
            'rendu'    => 'Rendu',
            'en_retard'=> 'En retard',
            default    => $this->statut,
        };
    }

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
