<?php

namespace App\Rules;

use App\Models\PasswordHistory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class NotInPasswordHistory implements ValidationRule
{
    public function __construct(private int $userId) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $history = PasswordHistory::where('user_id', $this->userId)
            ->latest('created_at')
            ->take(3)
            ->pluck('password');

        foreach ($history as $oldHash) {
            if (Hash::check($value, $oldHash)) {
                $fail('Le mot de passe ne doit pas être identique à l\'un de vos 3 derniers mots de passe.');
                return;
            }
        }
    }
}
