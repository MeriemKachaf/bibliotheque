@extends('layouts.app')

@section('title', 'Catalogue des livres')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-journals me-2 text-primary"></i>Catalogue des livres</h4>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('livres.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Ajouter un livre
        </a>
    @else
        <a href="{{ route('emprunts.create') }}" class="btn btn-primary">
            <i class="bi bi-arrow-right-circle me-1"></i>Emprunter un livre
        </a>
    @endif
</div>

{{-- Filtres avancés --}}
<div class="card mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
        <span class="fw-semibold small"><i class="bi bi-funnel me-1 text-primary"></i>Recherche avancée</span>
        <a href="{{ route('livres.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-x-lg me-1"></i>Réinitialiser
        </a>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('livres.index') }}" class="row g-3 align-items-end">

            {{-- Mot-clé --}}
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Mot-clé</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Titre, auteur, ISBN, éditeur..."
                           value="{{ $search }}">
                </div>
            </div>

            {{-- Catégorie --}}
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Catégorie</label>
                <select name="categorie_id" class="form-select">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ $categorieId == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Année --}}
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Année (de)</label>
                <input type="number" name="annee_min" class="form-control"
                       placeholder="ex: 2000" min="1800" max="{{ date('Y') }}"
                       value="{{ $anneeMin }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Année (à)</label>
                <input type="number" name="annee_max" class="form-control"
                       placeholder="ex: 2024" min="1800" max="{{ date('Y') }}"
                       value="{{ $anneeMax }}">
            </div>

            {{-- Tri --}}
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Trier par</label>
                <select name="sort" class="form-select">
                    <option value="titre"            {{ $sort === 'titre'            ? 'selected' : '' }}>Titre</option>
                    <option value="auteur"           {{ $sort === 'auteur'           ? 'selected' : '' }}>Auteur</option>
                    <option value="annee_publication"{{ $sort === 'annee_publication'? 'selected' : '' }}>Année</option>
                    <option value="quantite"         {{ $sort === 'quantite'         ? 'selected' : '' }}>Disponibilité</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Ordre</label>
                <select name="direction" class="form-select">
                    <option value="asc"  {{ $direction === 'asc'  ? 'selected' : '' }}>Croissant</option>
                    <option value="desc" {{ $direction === 'desc' ? 'selected' : '' }}>Décroissant</option>
                </select>
            </div>

            {{-- Disponibles uniquement --}}
            <div class="col-md-3 d-flex align-items-end">
                <div class="form-check mb-2">
                    <input type="checkbox" name="disponible" value="1" id="disponible"
                           class="form-check-input" {{ $disponible ? 'checked' : '' }}>
                    <label for="disponible" class="form-check-label small fw-semibold">
                        Disponibles uniquement
                    </label>
                </div>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i>Filtrer
                </button>
            </div>

        </form>
    </div>
</div>

{{-- Grille de livres --}}
@if($livres->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-search fs-1 d-block mb-3 opacity-50"></i>
        <p>Aucun livre trouvé.</p>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 mb-4">
        @foreach($livres as $livre)
            <div class="col">
                <div class="card h-100 livre-card">
                    <a href="{{ route('livres.show', $livre) }}">
                        <img src="{{ $livre->photo_url }}" alt="{{ $livre->titre }}"
                             class="card-img-top"
                             style="height:200px;object-fit:cover">
                    </a>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            @if($livre->categorie)
                                <span class="badge bg-light text-dark border">{{ $livre->categorie->nom }}</span>
                            @else
                                <span class="badge bg-light text-muted border">Non classé</span>
                            @endif
                            @if($livre->quantite > 0)
                                <span class="badge badge-disponible">{{ $livre->quantite }} dispo.</span>
                            @else
                                <span class="badge badge-indisponible">Indisponible</span>
                            @endif
                        </div>
                        <h6 class="fw-bold mb-1">{{ $livre->titre }}</h6>
                        <p class="text-muted small mb-1">
                            <i class="bi bi-person me-1"></i>{{ $livre->auteur }}
                        </p>
                        @if($livre->editeur)
                            <p class="text-muted small mb-1">
                                <i class="bi bi-building me-1"></i>{{ $livre->editeur }}
                                @if($livre->annee_publication) — {{ $livre->annee_publication }} @endif
                            </p>
                        @endif
                        @if($livre->description)
                            <p class="text-muted small mb-0" style="overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">
                                {{ $livre->description }}
                            </p>
                        @endif
                    </div>
                    <div class="card-footer bg-white d-flex gap-2">
                        @if(auth()->user()->isAdmin())
                            {{-- Admin : modifier / supprimer --}}
                            <a href="{{ route('livres.edit', $livre) }}" class="btn btn-sm btn-outline-warning flex-fill">
                                <i class="bi bi-pencil me-1"></i>Modifier
                            </a>
                            <form action="{{ route('livres.destroy', $livre) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Supprimer ce livre ?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @else
                            {{-- Membre : emprunter --}}
                            @if($livre->quantite > 0)
                                <a href="{{ route('emprunts.create', ['livre_id' => $livre->id]) }}"
                                   class="btn btn-sm btn-primary flex-fill">
                                    <i class="bi bi-arrow-right-circle me-1"></i>Emprunter
                                </a>
                            @else
                                <button class="btn btn-sm btn-outline-secondary flex-fill" disabled>
                                    Indisponible
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {{ $livres->withQueryString()->links() }}
    </div>
@endif
@endsection
