<?php

namespace Tests\Unit;

use App\Models\Livre;
use Tests\TestCase;

class LivreTest extends TestCase
{
    // Un livre avec des exemplaires disponibles doit être empruntable
    #[\PHPUnit\Framework\Attributes\Test]
    public function livre_est_disponible_si_quantite_positive(): void
    {
        $livre = new Livre(['quantite' => 3]);
        $this->assertTrue($livre->isDisponible());
    }

    // Un livre sans exemplaire ne doit pas pouvoir être emprunté
    #[\PHPUnit\Framework\Attributes\Test]
    public function livre_est_indisponible_si_quantite_zero(): void
    {
        $livre = new Livre(['quantite' => 0]);
        $this->assertFalse($livre->isDisponible());
    }

    // Un livre sans photo doit afficher une image par défaut et non une erreur
    #[\PHPUnit\Framework\Attributes\Test]
    public function livre_sans_photo_affiche_placeholder(): void
    {
        $livre = new Livre(['photo' => null]);
        $this->assertStringContainsString('livre-placeholder.png', $livre->photo_url);
    }

    // Un livre avec une photo doit afficher la bonne image dans le catalogue
    #[\PHPUnit\Framework\Attributes\Test]
    public function livre_avec_photo_affiche_son_image(): void
    {
        $livre = new Livre(['photo' => 'mon-livre.jpg']);
        $this->assertStringContainsString('mon-livre.jpg', $livre->photo_url);
        $this->assertStringContainsString('images/livres', $livre->photo_url);
    }
}
