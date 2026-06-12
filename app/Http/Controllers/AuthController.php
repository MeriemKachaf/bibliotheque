<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

// CONTRÔLEUR AuthController — gère la connexion, l'inscription et la déconnexion
class AuthController extends Controller
{
    // Affiche la page de connexion
    public function showLogin()
    {
        return view('auth.login');
    }

    // Traite le formulaire de connexion
    public function login(Request $request)
    {
        // Validation : email valide et mot de passe obligatoire
        $credentials = $request->validate([
            'email'    => ['required', 'email:rfc', 'regex:/^[^@]+@[^@]+\.[^@]{2,}$/'],
            'password' => 'required',
        ]);

        // Vérifie les identifiants — si correct, connecte l'utilisateur
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate(); // regénère la session (protection fixation de session)
            Log::info('Connexion réussie', ['email' => $request->email, 'ip' => $request->ip()]);
            ActivityLog::record('connexion', 'Connexion réussie');
            return redirect()->intended(route('dashboard'));
        }

        // Si identifiants incorrects : log l'échec et renvoie une erreur
        Log::warning('Tentative de connexion échouée', ['email' => $request->email, 'ip' => $request->ip()]);
        return back()->withErrors(['email' => 'Email ou mot de passe incorrect.'])->onlyInput('email');
    }

    // Affiche la page d'inscription
    public function showRegister()
    {
        return view('auth.register');
    }

    // Traite le formulaire d'inscription
    public function register(Request $request)
    {
        // Validation : mot de passe min 12 caractères, majuscules, chiffres, symboles
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['required', 'email:rfc', 'regex:/^[^@]+@[^@]+\.[^@]{2,}$/', 'unique:users'], // email unique avec domaine valide
            'telephone' => 'nullable|string|max:20',
            'password'  => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()->symbols()],
        ]);

        // Crée le compte avec le rôle "membre" par défaut et le mot de passe hashé
        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'telephone' => $data['telephone'] ?? null,
            'role'      => 'membre',
            'password'  => Hash::make($data['password']),
        ]);

        Auth::login($user); // connecte directement après inscription
        Log::info('Nouveau compte créé', ['email' => $user->email, 'ip' => $request->ip()]);
        ActivityLog::record('inscription', "Nouveau compte : {$user->email}");

        return redirect()->route('dashboard')->with('success', 'Compte créé avec succès. Bienvenue !');
    }

    // Déconnecte l'utilisateur et détruit la session
    public function logout(Request $request)
    {
        Log::info('Déconnexion', ['user_id' => auth()->id(), 'ip' => $request->ip()]);
        ActivityLog::record('déconnexion', 'Déconnexion');
        Auth::logout();
        $request->session()->invalidate();      // détruit la session
        $request->session()->regenerateToken(); // regénère le token CSRF
        return redirect()->route('login');
    }
}
