@extends('layouts.app')

@section('title', 'Ajouter un livre')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('livres.index') }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Ajouter un livre</h4>
        <p class="text-muted small mb-0">Remplissez les informations du nouveau livre</p>
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

<form action="{{ route('livres.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row g-4">

    {{-- Colonne gauche : photo --}}
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="fw-bold mb-0"><i class="bi bi-image me-2 text-primary"></i>Couverture</h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                <img id="preview" src="{{ asset('images/livre-placeholder.png') }}"
                     alt="Aperçu" class="rounded mb-3 shadow-sm"
                     style="width:130px;height:182px;object-fit:cover">
                <label for="photo" class="btn btn-outline-primary btn-sm w-100">
                    <i class="bi bi-upload me-1"></i>Choisir une image
                </label>
                <input type="file" name="photo" id="photo" class="d-none"
                       accept="image/*" onchange="previewPhoto(this)">
                <p class="text-muted mt-2 mb-0" style="font-size:.72rem;text-align:center">
                    JPG, PNG, WEBP — max 2 Mo
                </p>
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
                               value="{{ old('titre') }}" placeholder="Ex : Le Petit Prince" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Auteur <span class="text-danger">*</span></label>
                        <input type="text" name="auteur" class="form-control"
                               value="{{ old('auteur') }}" placeholder="Ex : Antoine de Saint-Exupéry" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Catégorie</label>
                        <select name="categorie_id" class="form-select">
                            <option value="">— Choisir —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>
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
                               value="{{ old('isbn') }}" placeholder="978-...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Éditeur</label>
                        <input type="text" name="editeur" class="form-control"
                               value="{{ old('editeur') }}" placeholder="Ex : Gallimard">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Année</label>
                        <input type="number" name="annee_publication" class="form-control"
                               value="{{ old('annee_publication') }}" min="1000" max="{{ date('Y') }}"
                               placeholder="{{ date('Y') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantité <span class="text-danger">*</span></label>
                        <input type="number" name="quantite" class="form-control"
                               value="{{ old('quantite', 1) }}" min="0" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Résumé ou description du livre...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Enregistrer le livre
                </button>
                <a href="{{ route('livres.index') }}" class="btn btn-outline-secondary">Annuler</a>
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
