<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LivreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $isbnRule = 'nullable|string|unique:livres,isbn';
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $isbnRule .= ',' . $this->route('livre')->id;
        }

        return [
            'titre'            => 'required|string|max:255',
            'auteur'           => 'required|string|max:255',
            'isbn'             => $isbnRule,
            'editeur'          => 'nullable|string|max:255',
            'annee_publication'=> 'nullable|integer|min:1000|max:' . date('Y'),
            'description'      => 'nullable|string',
            'quantite'         => 'required|integer|min:0',
            'categorie_id'     => 'nullable|exists:categories,id',
            'photo'            => 'nullable|image|max:2048',
            'supprimer_photo'  => 'nullable|boolean',
        ];
    }
}
