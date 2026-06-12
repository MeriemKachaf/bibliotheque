<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\PasswordHistory;
use App\Rules\NotInPasswordHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

// CONTRÔLEUR ProfileController — gère le profil de l'utilisateur connecté
class ProfileController extends Controller
{
    // Affiche la page profil avec les derniers emprunts
    public function show()
    {
        $user     = auth()->user();
        $emprunts = $user->emprunts()->with('livre')->latest()->paginate(5);
        return view('profile.show', compact('user', 'emprunts'));
    }

    // Met à jour les informations du profil (nom, email, téléphone)
    public function update(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . auth()->id(), // unique sauf pour soi-même
            'telephone' => 'nullable|string|max:20',
        ]);
        auth()->user()->update($data);
        return back()->with('success', 'Profil mis à jour.');
    }

    // Change le mot de passe avec plusieurs critères de sécurité
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|current_password', // vérifie que l'ancien mdp est correct
            'password'         => [
                'required',
                'confirmed',
                Password::min(12)->mixedCase()->numbers()->symbols(), // min 12 car, maj, chiffres, symboles
                new NotInPasswordHistory($user->id), // ne doit pas être identique aux 3 derniers
            ],
        ]);

        // Sauvegarde l'ancien mot de passe dans l'historique avant de le changer
        PasswordHistory::create([
            'user_id'  => $user->id,
            'password' => $user->getRawOriginal('password'),
        ]);

        // Garde seulement les 3 dernières entrées de l'historique
        $idsToKeep = PasswordHistory::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(3)
            ->pluck('id');

        PasswordHistory::where('user_id', $user->id)
            ->whereNotIn('id', $idsToKeep)
            ->delete();

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Mot de passe modifié.');
    }

    // Supprime le compte de l'utilisateur (conformité RGPD)
    public function destroy(Request $request)
    {
        $request->validate(['password' => 'required|current_password']); // confirmation par mot de passe

        $user = auth()->user();
        // Log de niveau warning car c'est une action irréversible
        Log::warning('Compte supprimé (RGPD)', ['user_id' => $user->id, 'email' => $user->email, 'ip' => $request->ip()]);
        ActivityLog::record('suppression_compte', "Compte supprimé : {$user->email}", 'warning');

        Auth::logout();
        $user->delete();

        // Détruit la session et regénère le token pour sécuriser après suppression
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Votre compte a été supprimé.');
    }
}
