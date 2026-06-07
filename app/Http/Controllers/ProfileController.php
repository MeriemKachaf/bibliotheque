<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user     = auth()->user();
        $emprunts = $user->emprunts()->with('livre')->latest()->paginate(5);
        return view('profile.show', compact('user', 'emprunts'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . auth()->id(),
            'telephone' => 'nullable|string|max:20',
        ]);
        auth()->user()->update($data);
        return back()->with('success', 'Profil mis à jour.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()->symbols()],
        ]);
        auth()->user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Mot de passe modifié.');
    }

    public function destroy(Request $request)
    {
        $request->validate(['password' => 'required|current_password']);

        $user = auth()->user();
        Log::warning('Compte supprimé (RGPD)', ['user_id' => $user->id, 'email' => $user->email, 'ip' => $request->ip()]);
        ActivityLog::record('suppression_compte', "Compte supprimé : {$user->email}", 'warning');

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Votre compte a été supprimé.');
    }
}
