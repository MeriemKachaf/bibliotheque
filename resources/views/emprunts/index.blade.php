@extends('layouts.app')

@section('title', 'Emprunts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-arrow-left-right me-2 text-primary"></i>
        {{ auth()->user()->isAdmin() ? 'Tous les emprunts' : 'Mes emprunts' }}
    </h4>
    <div class="d-flex gap-2">
        @if(auth()->user()->isAdmin())
            <a href="{{ route('emprunts.exportPdf', request()->only('statut')) }}"
               class="btn btn-outline-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i>Exporter PDF
            </a>
        @else
            <a href="{{ route('emprunts.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Nouvel emprunt
            </a>
        @endif
    </div>
</div>

{{-- Filtres --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('emprunts.index') }}" class="row g-2 align-items-end">
            @if(auth()->user()->isAdmin())
                <div class="col-md-5">
                    <label class="form-label small fw-semibold">Recherche</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Nom membre ou titre livre..." value="{{ $search }}">
                    </div>
                </div>
            @endif
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    <option value="en_cours"  {{ $statut === 'en_cours'  ? 'selected' : '' }}>En cours</option>
                    <option value="en_retard" {{ $statut === 'en_retard' ? 'selected' : '' }}>En retard</option>
                    <option value="rendu"     {{ $statut === 'rendu'     ? 'selected' : '' }}>Rendus</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-funnel me-1"></i>Filtrer
                </button>
                <a href="{{ route('emprunts.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        @if(auth()->user()->isAdmin())<th>Membre</th>@endif
                        <th>Livre</th>
                        <th>Date emprunt</th>
                        <th>Retour prévu</th>
                        <th>Retour effectif</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($emprunts as $emprunt)
                        <tr class="{{ $emprunt->statut === 'en_retard' ? 'table-danger' : '' }}">
                            <td class="text-muted small">{{ $emprunt->id }}</td>
                            @if(auth()->user()->isAdmin())
                                <td>{{ $emprunt->user->name }}</td>
                            @endif
                            <td>
                                <div class="fw-semibold">{{ $emprunt->livre->titre }}</div>
                                <div class="text-muted small">{{ $emprunt->livre->auteur }}</div>
                            </td>
                            <td>{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                            <td>{{ $emprunt->date_retour_prevue->format('d/m/Y') }}</td>
                            <td>
                                {{ $emprunt->date_retour_effective
                                    ? $emprunt->date_retour_effective->format('d/m/Y')
                                    : '—' }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $emprunt->statut_badge }}">
                                    {{ $emprunt->statut_label }}
                                </span>
                            </td>
                            <td>
                                @if($emprunt->statut !== 'rendu')
                                    <form action="{{ route('emprunts.retour', $emprunt) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"
                                                onclick="return confirm('Confirmer le retour ?')">
                                            <i class="bi bi-check-lg me-1"></i>Retour
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin() ? 8 : 7 }}"
                                class="text-center text-muted py-4">
                                Aucun emprunt trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $emprunts->withQueryString()->links() }}
</div>
@endsection
