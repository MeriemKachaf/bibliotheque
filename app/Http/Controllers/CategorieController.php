<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::withCount('livres')->orderBy('nom')->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);
        Categorie::create($data);
        return back()->with('success', 'Catégorie ajoutée.');
    }

    public function update(Request $request, Categorie $categorie)
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:255|unique:categories,nom,' . $categorie->id,
            'description' => 'nullable|string',
        ]);
        $categorie->update($data);
        return back()->with('success', 'Catégorie modifiée.');
    }

    public function destroy(Categorie $categorie)
    {
        $categorie->delete();
        return back()->with('success', 'Catégorie supprimée.');
    }
}
