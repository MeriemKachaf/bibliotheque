<?php

namespace App\Http\Controllers;

use App\Http\Requests\LivreRequest;
use App\Models\Categorie;
use App\Models\Livre;
use Illuminate\Http\Request;

// CONTRÔLEUR LivreController — gère tout ce qui concerne les livres (CRUD)
class LivreController extends Controller
{
    // Dossier où sont stockées les photos des livres
    private string $uploadDir;

    public function __construct()
    {
        $this->uploadDir = public_path('images/livres');
    }

    // Affiche la liste des livres avec filtres et recherche
    public function index(Request $request)
    {
        $search      = $request->input('search', '');
        $categorieId = $request->input('categorie_id', '');
        $disponible  = $request->boolean('disponible');
        $anneeMin    = $request->input('annee_min', '');
        $anneeMax    = $request->input('annee_max', '');
        $sort        = $request->input('sort', 'titre');
        $direction   = $request->input('direction', 'asc');

        // Sécurité : on autorise seulement certains tris pour éviter les injections
        $allowedSorts = ['titre', 'auteur', 'annee_publication', 'quantite'];
        if (!in_array($sort, $allowedSorts)) $sort = 'titre';
        if (!in_array($direction, ['asc', 'desc'])) $direction = 'asc';

        // Requête avec filtres dynamiques — chaque filtre s'applique seulement s'il est renseigné
        $livres = Livre::with('categorie')
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%$search%")
                  ->orWhere('auteur', 'like', "%$search%")
                  ->orWhere('isbn', 'like', "%$search%")
                  ->orWhere('editeur', 'like', "%$search%");
            }))
            ->when($categorieId, fn($q) => $q->where('categorie_id', $categorieId))
            ->when($disponible,  fn($q) => $q->where('quantite', '>', 0))
            ->when($anneeMin,    fn($q) => $q->where('annee_publication', '>=', (int) $anneeMin))
            ->when($anneeMax,    fn($q) => $q->where('annee_publication', '<=', (int) $anneeMax))
            ->orderBy($sort, $direction)
            ->paginate(12); // 12 livres par page

        $categories = Categorie::orderBy('nom')->get();

        return view('livres.index', compact(
            'livres', 'search', 'categories', 'categorieId',
            'disponible', 'anneeMin', 'anneeMax', 'sort', 'direction'
        ));
    }

    // Affiche le formulaire d'ajout d'un livre (admin seulement)
    public function create()
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('livres.create', compact('categories'));
    }

    // Enregistre un nouveau livre en base de données
    public function store(LivreRequest $request)
    {
        $data = $request->validated(); // données déjà validées par LivreRequest

        // Si une photo est envoyée, on la sauvegarde dans public/images/livres/
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->savePhoto($request->file('photo'));
        }

        Livre::create($data);

        return redirect()->route('livres.index')->with('success', 'Livre ajouté avec succès.');
    }

    // Affiche le détail d'un livre avec ses emprunts en cours
    public function show(Livre $livre)
    {
        $livre->load('categorie', 'emprunts.user');
        $empruntsActifs = $livre->emprunts->whereIn('statut', ['en_cours', 'en_retard'])->count();
        return view('livres.show', compact('livre', 'empruntsActifs'));
    }

    // Affiche le formulaire de modification d'un livre (admin seulement)
    public function edit(Livre $livre)
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('livres.edit', compact('livre', 'categories'));
    }

    // Met à jour un livre en base de données
    public function update(LivreRequest $request, Livre $livre)
    {
        $data = $request->validated();

        // Si une nouvelle photo est envoyée, on supprime l'ancienne et on sauvegarde la nouvelle
        if ($request->hasFile('photo')) {
            $this->deletePhoto($livre->photo);
            $data['photo'] = $this->savePhoto($request->file('photo'));
        }

        // Si l'admin coche "supprimer la photo", on la supprime
        if ($request->boolean('supprimer_photo') && $livre->photo) {
            $this->deletePhoto($livre->photo);
            $data['photo'] = null;
        }

        $livre->update($data);

        return redirect()->route('livres.index')->with('success', 'Livre modifié avec succès.');
    }

    // Supprime un livre et sa photo
    public function destroy(Livre $livre)
    {
        $this->deletePhoto($livre->photo);
        $livre->delete();

        return redirect()->route('livres.index')->with('success', 'Livre supprimé avec succès.');
    }

    // Sauvegarde la photo avec un nom unique pour éviter les doublons
    private function savePhoto($file): string
    {
        $filename = uniqid('livre_') . '.' . $file->getClientOriginalExtension();
        $file->move($this->uploadDir, $filename);
        return $filename;
    }

    // Supprime la photo du serveur si elle existe
    private function deletePhoto(?string $filename): void
    {
        if ($filename && file_exists($this->uploadDir . '/' . $filename)) {
            unlink($this->uploadDir . '/' . $filename);
        }
    }
}
