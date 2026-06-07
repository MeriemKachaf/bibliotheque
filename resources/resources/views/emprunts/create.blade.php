@extends('layouts.app')

@section('title', 'Nouvel emprunt')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('livres.index') }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-arrow-right-circle me-2 text-primary"></i>Nouvel emprunt</h4>
        <p class="text-muted small mb-0">Sélectionnez un livre et les dates</p>
    </div>
</div>

<div class="row justify-content-center g-4">
    {{-- Aperçu du livre sélectionné --}}
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="fw-bold mb-0"><i class="bi bi-book me-2 text-primary"></i>Livre sélectionné</h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-4" id="book-preview">
                <img id="preview-img" src="{{ asset('images/livre-placeholder.png') }}"
                     alt="Couverture" class="rounded shadow-sm mb-3"
                     style="width:120px;height:168px;object-fit:cover">
                <p class="fw-semibold text-center small mb-1" id="preview-titre">—</p>
                <p class="text-muted text-center" style="font-size:.8rem" id="preview-auteur"></p>
            </div>
        </div>
    </div>

    {{-- Formulaire --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Informations</h6>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li class="small">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('emprunts.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Emprunteur</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Livre <span class="text-danger">*</span></label>
                        <select name="livre_id" id="livre-select" class="form-select" required>
                            <option value="">— Sélectionner un livre —</option>
                            @foreach($livres as $livre)
                                <option value="{{ $livre->id }}"
                                    data-photo="{{ $livre->photo_url }}"
                                    data-titre="{{ $livre->titre }}"
                                    data-auteur="{{ $livre->auteur }}"
                                    {{ old('livre_id', request('livre_id')) == $livre->id ? 'selected' : '' }}>
                                    {{ $livre->titre }} — {{ $livre->auteur }}
                                    ({{ $livre->quantite }} dispo.)
                                </option>
                            @endforeach
                        </select>
                        @if($livres->isEmpty())
                            <div class="form-text text-danger mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i>Aucun livre disponible en ce moment.
                            </div>
                        @endif
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label">Date d'emprunt <span class="text-danger">*</span></label>
                            <input type="date" name="date_emprunt" class="form-control"
                                   value="{{ old('date_emprunt', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Retour prévu <span class="text-danger">*</span></label>
                            <input type="date" name="date_retour_prevue" class="form-control"
                                   value="{{ old('date_retour_prevue', date('Y-m-d', strtotime('+14 days'))) }}" required>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill"
                                {{ $livres->isEmpty() ? 'disabled' : '' }}>
                            <i class="bi bi-check-lg me-1"></i>Valider l'emprunt
                        </button>
                        <a href="{{ route('livres.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Affiche la couverture du livre sélectionné
const select = document.getElementById('livre-select');
function updatePreview() {
    const opt = select.options[select.selectedIndex];
    if (opt.value) {
        document.getElementById('preview-img').src   = opt.dataset.photo || '{{ asset("images/livre-placeholder.png") }}';
        document.getElementById('preview-titre').textContent  = opt.dataset.titre || '';
        document.getElementById('preview-auteur').textContent = opt.dataset.auteur || '';
    } else {
        document.getElementById('preview-img').src   = '{{ asset("images/livre-placeholder.png") }}';
        document.getElementById('preview-titre').textContent  = '—';
        document.getElementById('preview-auteur').textContent = '';
    }
}
select.addEventListener('change', updatePreview);
updatePreview(); // Initialise si valeur déjà sélectionnée
</script>
@endpush
@endsection
