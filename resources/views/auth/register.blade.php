<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription — Bibliothèque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="auth-bg d-flex align-items-center justify-content-center py-5">

    <div class="col-md-5 col-lg-4 col-11">
        <div class="card auth-card p-4">

            <div class="text-center mb-4">
                <div class="auth-logo">
                    <i class="bi bi-book-fill text-white fs-4"></i>
                </div>
                <h4 class="fw-bold mb-1" style="color:#1e293b">Créer un compte</h4>
                <p class="text-muted small mb-0">Rejoignez la bibliothèque</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name') }}" placeholder="Jean Dupont" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Adresse email <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email') }}" placeholder="jean@email.com" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">
                        Téléphone <span class="text-muted fw-normal">(optionnel)</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="telephone" class="form-control"
                               value="{{ old('telephone') }}" placeholder="0600000000">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control"
                               placeholder="Min. 12 car., maj., chiffres et symboles" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="Répétez le mot de passe" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                    <i class="bi bi-person-plus me-2"></i>S'inscrire
                </button>
            </form>

            <hr class="my-3">
            <p class="text-center small text-muted mb-0">
                Déjà un compte ?
                <a href="{{ route('login') }}" class="fw-semibold text-decoration-none" style="color:var(--primary)">
                    Se connecter
                </a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
