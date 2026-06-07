<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Bibliothèque')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="bi bi-book-fill me-2"></i>Bibliothèque
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                @auth
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                            @if(auth()->user()->isAdmin())
                                <span class="badge-admin ms-1">Admin</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="bi bi-person me-2 text-primary"></i>Mon profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        {{-- Sidebar --}}
        @auth
        <nav class="col-md-2 d-none d-md-block sidebar py-2 px-2">
            <div class="nav flex-column">

                @if(auth()->user()->isAdmin())
                {{-- ── MENU ADMIN ── --}}
                <span class="sidebar-section">Tableau de bord</span>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>Vue générale
                </a>

                <hr>
                <span class="sidebar-section">Catalogue</span>
                <a href="{{ route('livres.index') }}" class="nav-link {{ request()->routeIs('livres.index') || request()->routeIs('livres.show') ? 'active' : '' }}">
                    <i class="bi bi-journals"></i>Livres
                </a>
                <a href="{{ route('livres.create') }}" class="nav-link {{ request()->routeIs('livres.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>Ajouter un livre
                </a>
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i>Catégories
                </a>

                <hr>
                <span class="sidebar-section">Gestion</span>
                <a href="{{ route('emprunts.index') }}" class="nav-link {{ request()->routeIs('emprunts.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i>Tous les emprunts
                </a>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>Membres
                </a>

                <hr>
                <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person"></i>Mon profil
                </a>

                @else
                {{-- ── MENU MEMBRE ── --}}
                <span class="sidebar-section">Menu</span>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>Tableau de bord
                </a>
                <a href="{{ route('livres.index') }}" class="nav-link {{ request()->routeIs('livres.*') ? 'active' : '' }}">
                    <i class="bi bi-journals"></i>Catalogue
                </a>

                <hr>
                <span class="sidebar-section">Mes emprunts</span>
                <a href="{{ route('emprunts.index') }}" class="nav-link {{ request()->routeIs('emprunts.index') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>Mes emprunts
                </a>
                <a href="{{ route('emprunts.create') }}" class="nav-link {{ request()->routeIs('emprunts.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>Emprunter un livre
                </a>

                <hr>
                <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person"></i>Mon profil
                </a>
                @endif

            </div>
        </nav>
        @endauth

        {{-- Contenu principal --}}
        <main class="{{ auth()->check() ? 'col-md-10' : 'col-12' }} pt-4 pb-2 px-4">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<footer class="site-footer">
    <span><i class="bi bi-book-fill me-1"></i>Bibliothèque</span>
    <span class="footer-divider">·</span>
    <span>© {{ date('Y') }} — Tous droits réservés</span>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
