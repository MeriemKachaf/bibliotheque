<?php

namespace App\Rules;

use App\Models\PasswordHistory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

// RÈGLE DE VALIDATION personnalisée — vérifie que le nouveau mot de passe
// n'est pas identique à l'un des 3 derniers mots de passe utilisés
class NotInPasswordHistory implements ValidationRule
{
    // Reçoit l'id de l'utilisateur pour chercher son historique
    public function __construct(private int $userId) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Récupère les 3 derniers mots de passe hashés de l'utilisateur
        $history = PasswordHistory::where('user_id', $this->userId)
            ->latest('created_at')
            ->take(3)
            ->pluck('password');

        // Compare le nouveau mot de passe avec chaque ancien hash
        // Hash::check() permet de comparer un texte brut avec un hash bcrypt
        foreach ($history as $oldHash) {
            if (Hash::check($value, $oldHash)) {
                $fail('Le mot de passe ne doit pas être identique à l\'un de vos 3 derniers mots de passe.');
                return;
            }
        }
    }
}
