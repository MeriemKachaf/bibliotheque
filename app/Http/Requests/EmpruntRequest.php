<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpruntRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'livre_id'           => 'required|exists:livres,id',
            'date_emprunt'       => 'required|date',
            'date_retour_prevue' => 'required|date|after:date_emprunt',
        ];
    }
}
