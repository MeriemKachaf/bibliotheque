@extends('layouts.app')

@section('title', $livre->titre)

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('livres.index') }}">Catalogue</a></li>
        <li class="breadcrumb-item active">{{ Str::limit($livre->titre, 40) }}</li>
    </ol>
</nav>

<div class="row g-4">
    {{-- Photo --}}
    <div class="col-md-3 text-center">
        <img src="{{ $livre->photo_url }}" alt="{{ $livre->titre }}"
             class="img-fluid rounded shadow"
             style="max-height:320px;object-fit:cover;width:100%">
    </div>

    {{-- Détails du livre --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        @if($livre->categorie)
                            <span class="badge bg-light text-dark border mb-2">
                                <i class="bi bi-tag me-1"></i>{{ $livre->categorie->nom }}
                            </span>
                        @endif
                        <h4 class="fw-bold mb-1">{{ $livre->titre }}</h4>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $livre->auteur }}
                        </p>
                    </div>
                    @if($livre->quantite > 0)
                        <span class="badge badge-disponible fs-6">{{ $livre->quantite }} dispo.</span>
                    @else
                        <span class="badge badge-indisponible fs-6">Indisponible</span>
                    @endif
                </div>

                <hr>

                <div class="row g-2 mb-3">
                    @if($livre->isbn)
                        <div class="col-md-6">
                            <p class="mb-1 small text-muted">ISBN</p>
                            <p class="fw-semibold">{{ $livre->isbn }}</p>
                        </div>
                    @endif
                    @if($livre->editeur)
                        <div class="col-md-6">
                            <p class="mb-1 small text-muted">Éditeur</p>
                            <p class="fw-semibold">{{ $livre->editeur }}</p>
                        </div>
                    @endif
                    @if($livre->annee_publication)
                        <div class="col-md-6">
                            <p class="mb-1 small text-muted">Année de publication</p>
                            <p class="fw-semibold">{{ $livre->annee_publication }}</p>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <p class="mb-1 small text-muted">Emprunts en cours</p>
                        <p class="fw-semibold">{{ $empruntsActifs }}</p>
                    </div>
                </div>

                @if($livre->description)
                    <div class="mb-4">
                        <p class="mb-1 small text-muted fw-semibold text-uppercase">Description</p>
                        <p class="text-secondary">{{ $livre->description }}</p>
                    </div>
                @endif

                <div class="d-flex gap-2">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('livres.edit', $livre) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i>Modifier ce livre
                        </a>
                    @else
                        @if($livre->quantite > 0)
                            <a href="{{ route('emprunts.create', ['livre_id' => $livre->id]) }}"
                               class="btn btn-primary">
                                <i class="bi bi-arrow-right-circle me-1"></i>Emprunter ce livre
                            </a>
                        @else
                            <button class="btn btn-outline-secondary" disabled>Indisponible</button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Historique des emprunts --}}
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-clock-history me-2"></i>Historique des emprunts
                </h6>
            </div>
            <div class="card-body p-0" style="max-height:400px;overflow-y:auto">
                <ul class="list-group list-group-flush">
                    @forelse($livre->emprunts->sortByDesc('created_at') as $emprunt)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold small">{{ $emprunt->user->name }}</div>
                                    <div class="text-muted" style="font-size:.75rem">
                                        {{ $emprunt->date_emprunt->format('d/m/Y') }} →
                                        {{ $emprunt->date_retour_prevue->format('d/m/Y') }}
                                    </div>
                                </div>
                                <span class="badge bg-{{ $emprunt->statut_badge }}">
                                    {{ $emprunt->statut_label }}
                                </span>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-3">
                            Aucun emprunt pour ce livre.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
