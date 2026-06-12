<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

// CONTRÔLEUR UserController — gestion des utilisateurs (admin seulement)
class UserController extends Controller
{
    // Affiche la liste de tous les membres avec leurs statistiques d'emprunts
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $users = User::withCount([
                'emprunts',                                                          // total des emprunts
                'emprunts as emprunts_en_cours_count' => fn($q) => $q->where('statut', 'en_cours'), // emprunts actifs
            ])
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%")
                                        ->orWhere('email', 'like', "%$search%"))
            ->orderBy('name')
            ->paginate(15);

        return view('users.index', compact('users', 'search'));
    }

    // Bascule le rôle d'un utilisateur entre "membre" et "admin"
    public function toggleRole(User $user)
    {
        // Sécurité : on ne peut pas rétrograder le dernier admin (sinon plus personne pour gérer l'app)
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Impossible de rétrograder le dernier administrateur.');
        }

        $user->update(['role' => $user->isAdmin() ? 'membre' : 'admin']);
        return back()->with('success', 'Rôle modifié.');
    }

    // Supprime un utilisateur
    public function destroy(User $user)
    {
        // Sécurité : un admin ne peut pas supprimer son propre compte depuis cette page
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }
}
