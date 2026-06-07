<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    // Un admin doit avoir accès aux fonctionnalités de gestion (livres, membres...)
    #[\PHPUnit\Framework\Attributes\Test]
    public function utilisateur_admin_est_reconnu_comme_admin(): void
    {
        $user = new User(['role' => 'admin']);
        $this->assertTrue($user->isAdmin());
    }

    // Un membre ne doit pas avoir accès aux fonctionnalités d'administration
    #[\PHPUnit\Framework\Attributes\Test]
    public function utilisateur_membre_nest_pas_admin(): void
    {
        $user = new User(['role' => 'membre']);
        $this->assertFalse($user->isAdmin());
    }
}
