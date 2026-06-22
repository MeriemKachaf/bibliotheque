<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Emprunt;
use App\Models\Livre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// CONTRÔLEUR EmpruntController — gère les emprunts et les retours de livres
class EmpruntController extends Controller
{
    // Affiche la liste des emprunts
    // Admin : voit tous les emprunts — Membre : voit seulement les siens
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $statut = $request->input('statut', '');

        $query = Emprunt::with('user', 'livre')->latest();

        if (!auth()->user()->isAdmin()) {
            // Un membre ne voit que ses propres emprunts
            $query->where('user_id', auth()->id());
        } elseif ($search) {
            // L'admin peut rechercher par nom d'utilisateur ou titre de livre
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$search%"))
                ->orWhereHas('livre', fn($q) => $q->where('titre', 'like', "%$search%"));
        }

        if ($statut) {
            $query->where('statut', $statut);
        }

        $emprunts = $query->paginate(15);

        return view('emprunts.index', compact('emprunts', 'search', 'statut'));
    }

    // Affiche le formulaire pour créer un emprunt (membres seulement)
    public function create(Request $request)
    {
        // Récupère uniquement les livres disponibles (quantité > 0)
        $livres  = Livre::where('quantite', '>', 0)->orderBy('titre')->get();
        $livreId = $request->input('livre_id');
        return view('emprunts.create', compact('livres', 'livreId'));
    }

    // Enregistre un nouvel emprunt avec toutes les vérifications nécessaires
    public function store(Request $request)
    {
        $data = $request->validate([
            'livre_id'           => 'required|exists:livres,id',
            'date_emprunt'       => 'required|date',
            'date_retour_prevue' => 'required|date|after:date_emprunt',
        ]);

        // Règle métier : maximum 2 emprunts simultanés par membre
        $empruntsActifs = Emprunt::where('user_id', auth()->id())
            ->where('statut', 'en_cours')
            ->count();

        if ($empruntsActifs >= 2) {
            return back()->with('error', 'Vous ne pouvez pas emprunter plus de 2 livres simultanément.');
        }

        // Règle métier : on ne peut pas emprunter le même livre deux fois en même temps
        $dejaEmprunte = Emprunt::where('user_id', auth()->id())
            ->where('livre_id', $data['livre_id'])
            ->where('statut', 'en_cours')
            ->exists();

        if ($dejaEmprunte) {
            return back()->with('error', 'Vous empruntez déjà ce livre.');
        }

        // Vérifie que le livre est toujours disponible au moment de l'emprunt
        $livre = Livre::findOrFail($data['livre_id']);
        if ($livre->quantite < 1) {
            return back()->with('error', 'Ce livre n\'est plus disponible.');
        }

        $data['user_id'] = auth()->id();
        $data['statut']  = 'en_cours';

        Emprunt::create($data);
        $livre->decrement('quantite'); // réduit la quantité disponible de 1
        Log::info('Emprunt créé', ['user_id' => auth()->id(), 'livre_id' => $data['livre_id']]);
        ActivityLog::record('emprunt', "Emprunt du livre : {$livre->titre}");

        return redirect()->route('emprunts.index')->with('success', 'Emprunt enregistré.');
    }

    // Enregistre le retour d'un livre
    public function retour(Emprunt $emprunt)
    {
        // Sécurité : un membre ne peut rendre que ses propres emprunts
        if (!auth()->user()->isAdmin() && $emprunt->user_id !== auth()->id()) {
            abort(403);
        }

        $emprunt->update([
            'statut'                => 'rendu',
            'date_retour_effective' => now(), // date réelle du retour
        ]);
        $emprunt->livre->increment('quantite'); // remet la quantité disponible à +1
        Log::info('Retour enregistré', ['user_id' => auth()->id(), 'emprunt_id' => $emprunt->id]);
        ActivityLog::record('retour', "Retour du livre : {$emprunt->livre->titre}");

        return back()->with('success', 'Retour enregistré.');
    }

    // Génère un export PDF des emprunts (vue HTML imprimable)
    public function exportPdf(Request $request)
    {
        $statut   = $request->input('statut', '');
        $query    = Emprunt::with('user', 'livre')->latest();

        // Admin voit tout, membre voit seulement les siens
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }
        if ($statut) {
            $query->where('statut', $statut);
        }

        $emprunts = $query->get();
        return view('emprunts.pdf', compact('emprunts', 'statut'));
    }
}
