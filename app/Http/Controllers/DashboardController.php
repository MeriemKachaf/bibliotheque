<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $stats = [
                'total_livres'      => Livre::count(),
                'total_emprunts'    => Emprunt::count(),
                'emprunts_en_cours' => Emprunt::where('statut', 'en_cours')->count(),
                'emprunts_en_retard'=> Emprunt::where('statut', 'en_retard')->count(),
                'total_membres'     => User::where('role', 'membre')->count(),
            ];

            $derniers_emprunts = Emprunt::with('user', 'livre')
                ->latest()
                ->take(5)
                ->get();

            // Emprunts des 6 derniers mois
            $moisLabels = [];
            $moisData   = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $moisLabels[] = $date->translatedFormat('M Y');
                $moisData[]   = Emprunt::whereYear('date_emprunt', $date->year)
                    ->whereMonth('date_emprunt', $date->month)
                    ->count();
            }

            // Répartition par catégorie
            $repartitionCats = Categorie::withCount('livres')
                ->orderByDesc('livres_count')
                ->get()
                ->map(fn($c) => ['nom' => $c->nom, 'total' => $c->livres_count]);

            return view('dashboard', compact(
                'stats', 'derniers_emprunts', 'moisLabels', 'moisData', 'repartitionCats'
            ));
        }

        $user = auth()->user();
        $stats = [
            'mes_emprunts_en_cours'  => $user->emprunts()->where('statut', 'en_cours')->count(),
            'mes_emprunts_en_retard' => $user->emprunts()->where('statut', 'en_retard')->count(),
            'mes_emprunts_rendus'    => $user->emprunts()->where('statut', 'rendu')->count(),
        ];

        $mes_emprunts = $user->emprunts()->with('livre')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'mes_emprunts'));
    }
}
