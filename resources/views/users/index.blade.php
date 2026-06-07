@extends('layouts.app')

@section('title', 'Gestion des membres')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-people me-2 text-primary"></i>Gestion des membres</h4>
</div>

<div class="card mb-4">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Rechercher par nom ou email..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-primary">Filtrer</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
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
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Rôle</th>
                        <th>Emprunts total</th>
                        <th>En cours</th>
                        <th>Inscrit le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="text-muted small">{{ $user->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                @if($user->id === auth()->id())
                                    <span class="badge bg-secondary" style="font-size:.7rem">Vous</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->telephone ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $user->role === 'admin' ? 'bg-warning text-dark' : 'bg-primary' }}">
                                    {{ $user->role === 'admin' ? 'Admin' : 'Membre' }}
                                </span>
                            </td>
                            <td>{{ $user->emprunts_count }}</td>
                            <td>
                                @if($user->emprunts_en_cours_count > 0)
                                    <span class="badge bg-warning text-dark">{{ $user->emprunts_en_cours_count }}</span>
                                @else
                                    <span class="text-muted">0</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('users.toggleRole', $user) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm btn-outline-secondary"
                                                    title="{{ $user->role === 'admin' ? 'Rétrograder en membre' : 'Promouvoir admin' }}"
                                                    onclick="return confirm('Changer le rôle de {{ $user->name }} ?')">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Supprimer le compte de {{ $user->name }} ?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Aucun utilisateur trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $users->withQueryString()->links() }}
</div>
@endsection
