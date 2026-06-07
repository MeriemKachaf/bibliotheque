<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — Bibliothèque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="auth-bg d-flex align-items-center justify-content-center py-5">

    <div class="col-md-4 col-lg-3 col-11">
        <div class="card auth-card p-4">

            <div class="text-center mb-4">
                <div class="auth-logo">
                    <i class="bi bi-book-fill text-white fs-4"></i>
                </div>
                <h4 class="fw-bold mb-1" style="color:#1e293b">Bibliothèque</h4>
                <p class="text-muted small mb-0">Connectez-vous à votre compte</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Adresse email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email') }}" placeholder="exemple@email.com"
                               required autofocus>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control"
                               placeholder="••••••••" required autocomplete="off">
                    </div>
                </div>
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Se souvenir de moi</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                </button>
            </form>

            <hr class="my-3">
            <p class="text-center small text-muted mb-0">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="fw-semibold text-decoration-none" style="color:var(--primary)">
                    Créer un compte
                </a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
