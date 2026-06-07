@extends('layouts.app')

@section('title', 'Modifier — ' . $livre->titre)

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('livres.show', $livre) }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-warning"></i>Modifier un livre</h4>
        <p class="text-muted small mb-0">{{ $livre->titre }}</p>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Erreurs de validation :</strong>
        <ul class="mb-0 mt-1 ps-3">
            @foreach($errors->all() as $error)
                <li class="small">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('livres.update', $livre) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="row g-4">

    {{-- Colonne gauche : photo --}}
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="fw-bold mb-0"><i class="bi bi-image me-2 text-primary"></i>Couverture</h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                <img id="preview" src="{{ $livre->photo_url }}"
                     alt="Couverture" class="rounded mb-3 shadow-sm"
                     style="width:130px;height:182px;object-fit:cover">
                <label for="photo" class="btn btn-outline-primary btn-sm w-100">
                    <i class="bi bi-upload me-1"></i>Changer l'image
                </label>
                <input type="file" name="photo" id="photo" class="d-none"
                       accept="image/*" onchange="previewPhoto(this)">
                <p class="text-muted mt-2 mb-0" style="font-size:.72rem;text-align:center">
                    Laisser vide pour garder l'actuelle
                </p>
                @if($livre->photo)
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox"
                               name="supprimer_photo" id="supprimer_photo" value="1">
                        <label class="form-check-label text-danger small" for="supprimer_photo">
                            Supprimer la photo
                        </label>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Colonne droite : infos --}}
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h6 class="fw-bold mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Informations générales</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Titre <span class="text-danger">*</span></label>
                        <input type="text" name="titre" class="form-control"
                               value="{{ old('titre', $livre->titre) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Auteur <span class="text-danger">*</span></label>
                        <input type="text" name="auteur" class="form-control"
                               value="{{ old('auteur', $livre->auteur) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Catégorie</label>
                        <select name="categorie_id" class="form-select">
                            <option value="">— Choisir —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('categorie_id', $livre->categorie_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="fw-bold mb-0"><i class="bi bi-list-ul me-2 text-primary"></i>Détails</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">ISBN</label>
                        <input type="text" name="isbn" class="form-control"
                               value="{{ old('isbn', $livre->isbn) }}" placeholder="978-...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Éditeur</label>
                        <input type="text" name="editeur" class="form-control"
                               value="{{ old('editeur', $livre->editeur) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Année</label>
                        <input type="number" name="annee_publication" class="form-control"
                               value="{{ old('annee_publication', $livre->annee_publication) }}"
                               min="1000" max="{{ date('Y') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantité <span class="text-danger">*</span></label>
                        <input type="number" name="quantite" class="form-control"
                               value="{{ old('quantite', $livre->quantite) }}" min="0" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $livre->description) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i>Mettre à jour
                </button>
                <a href="{{ route('livres.show', $livre) }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </div>
    </div>

</div>
</form>

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('preview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
