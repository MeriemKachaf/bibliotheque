<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') — Bibliothèque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center text-white">
    <div class="text-center p-4">
        <div class="display-1 fw-bold opacity-25 mb-2">@yield('code')</div>
        <i class="@yield('icon') display-3 mb-3 d-block"></i>
        <h2 class="fw-bold mb-2">@yield('title')</h2>
        <p class="text-white-50 mb-4">@yield('message')</p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ url()->previous() }}" class="btn btn-outline-light">
                <i class="bi bi-arrow-left me-1"></i>Retour
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="bi bi-house me-1"></i>Tableau de bord
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Se connecter
                </a>
            @endauth
        </div>
    </div>
</body>
</html>
