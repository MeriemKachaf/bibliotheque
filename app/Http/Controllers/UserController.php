<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $users = User::withCount([
                'emprunts',
                'emprunts as emprunts_en_cours_count' => fn($q) => $q->where('statut', 'en_cours'),
            ])
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%")
                                        ->orWhere('email', 'like', "%$search%"))
            ->orderBy('name')
            ->paginate(15);

        return view('users.index', compact('users', 'search'));
    }

    public function toggleRole(User $user)
    {
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Impossible de rétrograder le dernier administrateur.');
        }

        $user->update(['role' => $user->isAdmin() ? 'membre' : 'admin']);
        return back()->with('success', 'Rôle modifié.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }
}
