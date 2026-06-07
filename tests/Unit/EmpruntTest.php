<?php

namespace Tests\Unit;

use App\Models\Emprunt;
use Tests\TestCase;

class EmpruntTest extends TestCase
{
    // Un emprunt en cours doit afficher le badge orange pour alerter le bibliothécaire
    #[\PHPUnit\Framework\Attributes\Test]
    public function emprunt_en_cours_a_badge_warning(): void
    {
        $emprunt = new Emprunt(['statut' => 'en_cours']);
        $this->assertEquals('warning', $emprunt->statut_badge);
    }

    // Un emprunt en retard doit afficher le badge rouge pour alerter immédiatement
    #[\PHPUnit\Framework\Attributes\Test]
    public function emprunt_en_retard_a_badge_danger(): void
    {
        $emprunt = new Emprunt(['statut' => 'en_retard']);
        $this->assertEquals('danger', $emprunt->statut_badge);
    }

    // Un emprunt rendu doit afficher le badge vert pour confirmer le retour
    #[\PHPUnit\Framework\Attributes\Test]
    public function emprunt_rendu_a_badge_success(): void
    {
        $emprunt = new Emprunt(['statut' => 'rendu']);
        $this->assertEquals('success', $emprunt->statut_badge);
    }

    // Le label affiché doit être en français et lisible pour l'utilisateur
    #[\PHPUnit\Framework\Attributes\Test]
    public function emprunt_en_cours_a_label_francais(): void
    {
        $emprunt = new Emprunt(['statut' => 'en_cours']);
        $this->assertEquals('En cours', $emprunt->statut_label);
    }
}
