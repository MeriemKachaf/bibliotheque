<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// MIDDLEWARE SecurityHeadersMiddleware — ajoute des en-têtes de sécurité HTTP à chaque réponse
// Ces en-têtes protègent contre certaines attaques web courantes
class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Empêche le navigateur de deviner le type du fichier (protection contre l'injection de contenu)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Empêche la page d'être affichée dans une iframe (protection contre le clickjacking)
        $response->headers->set('X-Frame-Options', 'DENY');

        // Active la protection XSS du navigateur (attaque par injection de script)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Contrôle quelles informations sont envoyées dans l'en-tête Referer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
}
