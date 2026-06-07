@extends('layouts.app')

@section('title', 'Journal d\'activité')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-journal-text me-2 text-primary"></i>Journal d'activité</h4>
</div>

{{-- Filtre --}}
<div class="card mb-4">
    <div class="card-body py-2">
        <form method="GET" class="d-flex align-items-center gap-2 flex-wrap">
            <select name="action" class="form-select form-select-sm w-auto">
                <option value="">Toutes les actions</option>
                @foreach($actions as $a)
                    <option value="{{ $a }}" {{ $action === $a ? 'selected' : '' }}>{{ ucfirst($a) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-funnel me-1"></i>Filtrer
            </button>
            @if($action)
                <a href="{{ route('activity_logs.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x me-1"></i>Réinitialiser
                </a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date / Heure</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP</th>
                        <th>Niveau</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="small text-muted">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="small">{{ $log->user?->name ?? '—' }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $log->action }}</span>
                            </td>
                            <td class="small">{{ $log->description }}</td>
                            <td class="small text-muted font-monospace">{{ $log->ip }}</td>
                            <td>
                                <span class="badge {{ $log->niveau === 'warning' ? 'bg-danger' : 'bg-success' }}">
                                    {{ $log->niveau }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox d-block fs-3 mb-2 opacity-25"></i>
                                Aucun journal d'activité.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
        <div class="card-footer">{{ $logs->links() }}</div>
    @endif
</div>

@endsection
