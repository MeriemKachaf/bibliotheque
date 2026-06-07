@extends('layouts.app')

@section('title', 'Catégories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-tags me-2 text-primary"></i>Catégories</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAjout">
        <i class="bi bi-plus-lg me-1"></i>Nouvelle catégorie
    </button>
</div>

<div class="row g-3">
    @forelse($categories as $categorie)
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $categorie->nom }}</h6>
                            <p class="text-muted small mb-0">{{ $categorie->description ?? 'Aucune description' }}</p>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $categorie->livres_count }} livre(s)</span>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex gap-2">
                    <button class="btn btn-sm btn-outline-warning flex-fill"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEdit{{ $categorie->id }}">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </button>
                    <form action="{{ route('categories.destroy', $categorie) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Supprimer cette catégorie ?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal edit --}}
        <div class="modal fade" id="modalEdit{{ $categorie->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('categories.update', $categorie) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Modifier la catégorie</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nom</label>
                                <input type="text" name="nom" class="form-control" value="{{ $categorie->nom }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="2">{{ $categorie->description }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-warning">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 text-muted">
            <i class="bi bi-tags fs-1 d-block mb-3 opacity-50"></i>
            <p>Aucune catégorie créée.</p>
        </div>
    @endforelse
</div>

{{-- Modal ajout --}}
<div class="modal fade" id="modalAjout" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Nouvelle catégorie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
