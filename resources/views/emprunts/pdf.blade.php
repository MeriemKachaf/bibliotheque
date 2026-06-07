<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body        { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1a1a1a; }
        h1          { font-size: 18px; margin-bottom: 4px; }
        .subtitle   { font-size: 11px; color: #555; margin-bottom: 16px; }
        .info-line  { font-size: 11px; color: #444; margin-bottom: 20px; }

        table       { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead tr    { background-color: #1a1a2e; color: #fff; }
        th          { padding: 7px 8px; text-align: left; font-size: 11px; }
        td          { padding: 6px 8px; font-size: 11px; border-bottom: 1px solid #e5e7eb; }
        tr.alt      { background-color: #f9fafb; }

        .badge             { padding: 2px 7px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .badge-en_cours    { background: #fef9c3; color: #854d0e; }
        .badge-rendu       { background: #dcfce7; color: #166534; }
        .badge-en_retard   { background: #fee2e2; color: #991b1b; }

        .footer { margin-top: 30px; font-size: 10px; color: #888; text-align: center; }
    </style>
</head>
<body>

    <h1>Bibliothèque — Liste des emprunts</h1>
    <div class="subtitle">
        @if($statut)
            Filtre : <strong>{{ ['en_cours' => 'En cours', 'rendu' => 'Rendu', 'en_retard' => 'En retard'][$statut] ?? $statut }}</strong> —
        @endif
        {{ $emprunts->count() }} emprunt(s) exporté(s)
    </div>
    <div class="info-line">
        Généré le {{ now()->format('d/m/Y à H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Membre</th>
                <th>Livre</th>
                <th>Date emprunt</th>
                <th>Retour prévu</th>
                <th>Retour effectif</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($emprunts as $i => $emprunt)
                <tr class="{{ $i % 2 === 1 ? 'alt' : '' }}">
                    <td>{{ $emprunt->id }}</td>
                    <td>{{ $emprunt->user->name }}</td>
                    <td>{{ $emprunt->livre->titre }}</td>
                    <td>{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                    <td>{{ $emprunt->date_retour_prevue->format('d/m/Y') }}</td>
                    <td>{{ $emprunt->date_retour_effective ? $emprunt->date_retour_effective->format('d/m/Y') : '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $emprunt->statut }}">
                            {{ $emprunt->statut_label }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:#888">Aucun emprunt.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Document généré automatiquement — Bibliothèque &copy; {{ date('Y') }}
    </div>

</body>
</html>
