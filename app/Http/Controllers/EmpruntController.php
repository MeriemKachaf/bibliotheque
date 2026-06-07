<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Emprunt;
use App\Models\Livre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmpruntController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $statut = $request->input('statut', '');

        $query = Emprunt::with('user', 'livre')->latest();

        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        } elseif ($search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$search%"))
                  ->orWhereHas('livre', fn($q) => $q->where('titre', 'like', "%$search%"));
        }

        if ($statut) {
            $query->where('statut', $statut);
        }

        $emprunts = $query->paginate(15);

        return view('emprunts.index', compact('emprunts', 'search', 'statut'));
    }

    public function create(Request $request)
    {
        $livres  = Livre::where('quantite', '>', 0)->orderBy('titre')->get();
        $livreId = $request->input('livre_id');
        return view('emprunts.create', compact('livres', 'livreId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'livre_id'           => 'required|exists:livres,id',
            'date_emprunt'       => 'required|date',
            'date_retour_prevue' => 'required|date|after:date_emprunt',
        ]);

        $empruntsActifs = Emprunt::where('user_id', auth()->id())
            ->where('statut', 'en_cours')
            ->count();

        if ($empruntsActifs >= 2) {
            return back()->with('error', 'Vous ne pouvez pas emprunter plus de 2 livres simultanément.');
        }

        $dejaEmprunte = Emprunt::where('user_id', auth()->id())
            ->where('livre_id', $data['livre_id'])
            ->where('statut', 'en_cours')
            ->exists();

        if ($dejaEmprunte) {
            return back()->with('error', 'Vous empruntez déjà ce livre.');
        }

        $livre = Livre::findOrFail($data['livre_id']);
        if ($livre->quantite < 1) {
            return back()->with('error', 'Ce livre n\'est plus disponible.');
        }

        $data['user_id'] = auth()->id();
        $data['statut']  = 'en_cours';

        Emprunt::create($data);
        $livre->decrement('quantite');
        Log::info('Emprunt créé', ['user_id' => auth()->id(), 'livre_id' => $data['livre_id']]);
        ActivityLog::record('emprunt', "Emprunt du livre : {$livre->titre}");

        return redirect()->route('emprunts.index')->with('success', 'Emprunt enregistré.');
    }

    public function retour(Emprunt $emprunt)
    {
        $emprunt->update([
            'statut'                => 'rendu',
            'date_retour_effective' => now(),
        ]);
        $emprunt->livre->increment('quantite');
        Log::info('Retour enregistré', ['user_id' => auth()->id(), 'emprunt_id' => $emprunt->id]);
        ActivityLog::record('retour', "Retour du livre : {$emprunt->livre->titre}");

        return back()->with('success', 'Retour enregistré.');
    }

    public function exportPdf(Request $request)
    {
        $statut   = $request->input('statut', '');
        $query    = Emprunt::with('user', 'livre')->latest();

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
