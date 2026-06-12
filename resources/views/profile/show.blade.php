@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')

{{-- En-tête profil --}}
<div class="card mb-4">
    <div class="card-body py-4">
        <div class="d-flex align-items-center gap-4">
            <div class="avatar-lg">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <h4 class="fw-bold mb-1">{{ auth()->user()->name }}</h4>
                <p class="text-muted mb-1 small"><i class="bi bi-envelope me-1"></i>{{ auth()->user()->email }}</p>
                @if(auth()->user()->telephone)
                    <p class="text-muted mb-1 small"><i class="bi bi-telephone me-1"></i>{{ auth()->user()->telephone }}</p>
                @endif
                <span class="badge mt-1 {{ auth()->user()->isAdmin() ? 'bg-warning text-dark' : 'bg-primary' }}">
                    <i class="bi bi-{{ auth()->user()->isAdmin() ? 'shield-fill' : 'person-fill' }} me-1"></i>
                    {{ auth()->user()->isAdmin() ? 'Administrateur' : 'Membre' }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- Informations personnelles --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Informations personnelles</h6>
            </div>
            <div class="card-body">
                @if($errors->has('name') || $errors->has('email') || $errors->has('telephone'))
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 ps-3">
                            @foreach(['name','email','telephone'] as $field)
                                @error($field)<li class="small">{{ $message }}</li>@enderror
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nom complet</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $user->name) }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Téléphone <span class="text-muted fw-normal">(optionnel)</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="telephone" class="form-control"
                                   value="{{ old('telephone', $user->telephone) }}"
                                   placeholder="0600000000">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save me-1"></i>Enregistrer les modifications
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Changer mot de passe --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="fw-bold mb-0"><i class="bi bi-shield-lock me-2 text-primary"></i>Sécurité</h6>
            </div>
            <div class="card-body">
                @if($errors->has('current_password') || $errors->has('password'))
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 ps-3">
                            @foreach(['current_password','password'] as $field)
                                @error($field)<li class="small">{{ $message }}</li>@enderror
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Mot de passe actuel</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="password" class="form-control"
                                   placeholder="Min. 12 car., maj., chiffres et symboles" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirmer le nouveau mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-key me-1"></i>Changer le mot de passe
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Suppression de compte --}}
    <div class="col-12">
        <div class="card border-danger">
            <div class="card-header bg-danger bg-opacity-10">
                <h6 class="fw-bold mb-0 text-danger"><i class="bi bi-trash me-2"></i>Supprimer mon compte</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Cette action est <strong>irréversible</strong>. Toutes vos données seront définitivement supprimées (droit à l'effacement — RGPD).</p>
                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalSuppression">
                    <i class="bi bi-trash me-1"></i>Supprimer mon compte
                </button>
            </div>
        </div>
    </div>

    {{-- Modal confirmation suppression --}}
    <div class="modal fade" id="modalSuppression" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-danger">
                    <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('profile.destroy') }}" method="POST">
                    @csrf @method('DELETE')
                    <div class="modal-body">
                        <p class="text-muted small">Pour confirmer, entrez votre mot de passe actuel.</p>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Votre mot de passe" required autofocus>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash me-1"></i>Supprimer définitivement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Historique des emprunts --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Mes emprunts récents</h6>
                <a href="{{ route('emprunts.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Livre</th>
                                <th>Date emprunt</th>
                                <th>Retour prévu</th>
                                <th>Retour effectif</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($emprunts as $emprunt)
                                <tr>
                                    <td>
                                        <div class="fw-semibold small">{{ $emprunt->livre->titre }}</div>
                                        <div class="text-muted" style="font-size:.75rem">{{ $emprunt->livre->auteur }}</div>
                                    </td>
                                    <td class="small">{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                                    <td class="small">{{ $emprunt->date_retour_prevue->format('d/m/Y') }}</td>
                                    <td class="small">{{ $emprunt->date_retour_effective?->format('d/m/Y') ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $emprunt->statut_badge }}">
                                            {{ $emprunt->statut_label }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox d-block fs-3 mb-2 opacity-25"></i>
                                        Aucun emprunt.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($emprunts->hasPages())
                <div class="card-footer">{{ $emprunts->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
