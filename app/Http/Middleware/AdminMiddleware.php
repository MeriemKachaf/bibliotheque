<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// MIDDLEWARE AdminMiddleware — bloque l'accès aux pages réservées aux admins
// Utilisé sur toutes les routes du groupe "admin" dans routes/web.php
class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Si l'utilisateur n'est pas connecté OU n'est pas admin → erreur 403 (Accès refusé)
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403);
        }
        return $next($request); // tout va bien, on laisse passer la requête
    }
}
