<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email:rfc',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            Log::info('Connexion réussie', ['email' => $request->email, 'ip' => $request->ip()]);
            ActivityLog::record('connexion', 'Connexion réussie');
            return redirect()->intended(route('dashboard'));
        }

        Log::warning('Tentative de connexion échouée', ['email' => $request->email, 'ip' => $request->ip()]);
        return back()->withErrors(['email' => 'Email ou mot de passe incorrect.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email:rfc|unique:users',
            'telephone' => 'nullable|string|max:20',
            'password'  => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()->symbols()],
        ]);

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'telephone' => $data['telephone'] ?? null,
            'role'      => 'membre',
            'password'  => Hash::make($data['password']),
        ]);

        Auth::login($user);
        Log::info('Nouveau compte créé', ['email' => $user->email, 'ip' => $request->ip()]);
        ActivityLog::record('inscription', "Nouveau compte : {$user->email}");

        return redirect()->route('dashboard')->with('success', 'Compte créé avec succès. Bienvenue !');
    }

    public function logout(Request $request)
    {
        Log::info('Déconnexion', ['user_id' => auth()->id(), 'ip' => $request->ip()]);
        ActivityLog::record('déconnexion', 'Déconnexion');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
