@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">
            @if(auth()->user()->isAdmin())
                <i class="bi bi-speedometer2 me-2 text-primary"></i>Tableau de bord
            @else
                <i class="bi bi-book-open me-2 text-primary"></i>Bienvenue dans votre bibliothèque
            @endif
        </h4>
        <p class="text-muted small mb-0 mt-1">{{ now()->translatedFormat('l d F Y') }}</p>
    </div>
</div>

@if(auth()->user()->isAdmin())
{{-- ===== VUE ADMIN ===== --}}

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-md col-6">
        <div class="card stat-card border-0 p-3" style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="small mb-1 opacity-75 fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.5px">Livres</p>
                    <div class="stat-number">{{ $stats['total_livres'] }}</div>
                </div>
                <i class="bi bi-journals" style="font-size:2rem;opacity:.3"></i>
            </div>
        </div>
    </div>
    <div class="col-md col-6">
        <div class="card stat-card border-0 p-3" style="background:linear-gradient(135deg,#10b981,#059669);color:#fff">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="small mb-1 opacity-75 fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.5px">Total emprunts</p>
                    <div class="stat-number">{{ $stats['total_emprunts'] }}</div>
                </div>
                <i class="bi bi-arrow-left-right" style="font-size:2rem;opacity:.3"></i>
            </div>
        </div>
    </div>
    <div class="col-md col-6">
        <div class="card stat-card border-0 p-3" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="small mb-1 opacity-75 fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.5px">En cours</p>
                    <div class="stat-number">{{ $stats['emprunts_en_cours'] }}</div>
                </div>
                <i class="bi bi-clock" style="font-size:2rem;opacity:.3"></i>
            </div>
        </div>
    </div>
    <div class="col-md col-6">
        <div class="card stat-card border-0 p-3" style="background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="small mb-1 opacity-75 fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.5px">En retard</p>
                    <div class="stat-number">{{ $stats['emprunts_en_retard'] }}</div>
                </div>
                <i class="bi bi-exclamation-triangle" style="font-size:2rem;opacity:.3"></i>
            </div>
        </div>
    </div>
    <div class="col-md col-6">
        <div class="card stat-card border-0 p-3" style="background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="small mb-1 opacity-75 fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.5px">Membres</p>
                    <div class="stat-number">{{ $stats['total_membres'] }}</div>
                </div>
                <i class="bi bi-people" style="font-size:2rem;opacity:.3"></i>
            </div>
        </div>
    </div>
</div>

{{-- Graphiques --}}
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Emprunts — 6 derniers mois</h6>
            </div>
            <div class="card-body">
                <canvas id="chartMois" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="fw-bold mb-0"><i class="bi bi-pie-chart me-2 text-primary"></i>Par catégorie</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="chartCats" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Derniers emprunts --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Derniers emprunts</h6>
        <a href="{{ route('emprunts.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Membre</th>
                        <th>Livre</th>
                        <th>Emprunt</th>
                        <th>Retour prévu</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($derniers_emprunts as $emprunt)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm">{{ strtoupper(substr($emprunt->user->name,0,1)) }}</div>
                                    <span class="fw-semibold small">{{ $emprunt->user->name }}</span>
                                </div>
                            </td>
                            <td class="small">{{ $emprunt->livre->titre }}</td>
                            <td class="small text-muted">{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                            <td class="small text-muted">{{ $emprunt->date_retour_prevue->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $emprunt->statut_badge }}">{{ $emprunt->statut_label }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Aucun emprunt.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('chartMois'), {
        type: 'bar',
        data: {
            labels: @json($moisLabels),
            datasets: [{
                label: 'Emprunts',
                data: @json($moisData),
                backgroundColor: 'rgba(99,102,241,.75)',
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });

    const catData = @json($repartitionCats);
    new Chart(document.getElementById('chartCats'), {
        type: 'doughnut',
        data: {
            labels: catData.map(c => c.nom),
            datasets: [{
                data: catData.map(c => c.total),
                backgroundColor: ['#6366f1','#10b981','#f59e0b','#ef4444','#0ea5e9','#8b5cf6','#f97316'],
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            cutout: '65%',
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 12, font: { size: 11 } } } }
        }
    });
</script>
@endpush

@else
{{-- ===== VUE MEMBRE ===== --}}

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card border-0 p-3" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="small mb-1 opacity-75 fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.5px">En cours</p>
                    <div class="stat-number">{{ $stats['mes_emprunts_en_cours'] }}</div>
                </div>
                <i class="bi bi-clock" style="font-size:2rem;opacity:.3"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card border-0 p-3" style="background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="small mb-1 opacity-75 fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.5px">En retard</p>
                    <div class="stat-number">{{ $stats['mes_emprunts_en_retard'] }}</div>
                </div>
                <i class="bi bi-exclamation-triangle" style="font-size:2rem;opacity:.3"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card border-0 p-3" style="background:linear-gradient(135deg,#10b981,#059669);color:#fff">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="small mb-1 opacity-75 fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.5px">Rendus</p>
                    <div class="stat-number">{{ $stats['mes_emprunts_rendus'] }}</div>
                </div>
                <i class="bi bi-check-circle" style="font-size:2rem;opacity:.3"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <div class="card h-100 text-center">
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-5">
                <div style="width:64px;height:64px;background:var(--primary-light);border-radius:16px;display:flex;align-items:center;justify-content:center;margin-bottom:1rem">
                    <i class="bi bi-journals text-primary" style="font-size:1.75rem"></i>
                </div>
                <h5 class="fw-bold mb-1">Parcourir le catalogue</h5>
                <p class="text-muted small mb-4">Découvrez tous nos livres disponibles.</p>
                <a href="{{ route('livres.index') }}" class="btn btn-primary px-4">
                    <i class="bi bi-search me-2"></i>Voir les livres
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Mes derniers emprunts</h6>
                <a href="{{ route('emprunts.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($mes_emprunts as $emprunt)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold small">{{ $emprunt->livre->titre }}</div>
                                <div class="text-muted" style="font-size:.75rem">
                                    <i class="bi bi-calendar3 me-1"></i>Retour prévu : {{ $emprunt->date_retour_prevue->format('d/m/Y') }}
                                </div>
                            </div>
                            <span class="badge bg-{{ $emprunt->statut_badge }}">{{ $emprunt->statut_label }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted text-center py-4">
                            <i class="bi bi-inbox d-block fs-3 mb-2 opacity-25"></i>
                            Aucun emprunt pour l'instant.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
