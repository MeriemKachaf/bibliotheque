<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LivreController;
use App\Http\Controllers\EmpruntController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

Route::get('/', fn() => redirect()->route('dashboard'));

// Authentification (guests uniquement)
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {

    // Commun (admin + membre)
    Route::get('/dashboard',         [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile',           [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile',           [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',  [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Catalogue livres
    Route::get('/livres', [LivreController::class, 'index'])->name('livres.index');

    // ── ADMIN seulement ──────────────────────────────────────────────
    Route::middleware('admin')->group(function () {
        // Routes statiques AVANT les routes dynamiques {livre}
        Route::get('/livres/create',        [LivreController::class, 'create'])->name('livres.create');
        Route::post('/livres',              [LivreController::class, 'store'])->name('livres.store');
        Route::get('/livres/{livre}/edit',  [LivreController::class, 'edit'])->name('livres.edit');
        Route::put('/livres/{livre}',       [LivreController::class, 'update'])->name('livres.update');
        Route::delete('/livres/{livre}',    [LivreController::class, 'destroy'])->name('livres.destroy');

        Route::get('/categories',                [CategorieController::class, 'index'])->name('categories.index');
        Route::post('/categories',               [CategorieController::class, 'store'])->name('categories.store');
        Route::put('/categories/{categorie}',    [CategorieController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{categorie}', [CategorieController::class, 'destroy'])->name('categories.destroy');

        Route::get('/users',                      [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/toggle-role', [UserController::class, 'toggleRole'])->name('users.toggleRole');
        Route::delete('/users/{user}',            [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Route dynamique livres APRÈS les routes statiques
    Route::get('/livres/{livre}', [LivreController::class, 'show'])->name('livres.show');

    // Emprunts — lecture + retour pour tous (le contrôleur filtre par rôle)
    Route::get('/emprunts',                   [EmpruntController::class, 'index'])->name('emprunts.index');
    Route::get('/emprunts/export-pdf',        [EmpruntController::class, 'exportPdf'])->name('emprunts.exportPdf');
    Route::post('/emprunts/{emprunt}/retour', [EmpruntController::class, 'retour'])->name('emprunts.retour');

    // ── MEMBRE seulement ─────────────────────────────────────────────
    Route::middleware('membre')->group(function () {
        // Route statique AVANT la route dynamique {emprunt}
        Route::get('/emprunts/create', [EmpruntController::class, 'create'])->name('emprunts.create');
        Route::post('/emprunts',       [EmpruntController::class, 'store'])->name('emprunts.store');
    });
});
