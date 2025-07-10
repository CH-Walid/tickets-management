<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Voici les routes web de ton application. Ce fichier gère
| l'inscription (user_simple uniquement), la connexion, la déconnexion,
| et la redirection selon le rôle. Les vues manquantes sont évitées.
|
*/

// ==== Authentification ====

Route::middleware(['guest'])->group(function () {

    // Formulaire de connexion
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

    // Traitement de connexion
    Route::post('/login', [AuthController::class, 'login']);

    // Formulaire d'inscription (user_simple uniquement)
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    // Traitement de l'inscription
    Route::post('/register', [AuthController::class, 'register']);

    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ==== Dashboards protégés ====

// Dashboard de l'utilisateur simple
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', function () {
        return 'Bienvenue sur le dashboard utilisateur simple (vue non encore créée).';
    })->name('user.dashboard');

    Route::get('/admin/dashboard', function () {
        return 'Dashboard admin (non créé)';
    })->name('admin.dashboard');
    
    Route::get('/chef/dashboard', function () {
        return 'Dashboard chef (non créé)';
    })->name('chef.dashboard');
    
    Route::get('/tech/dashboard', function () {
        return 'Dashboard technicien (non créé)';
    })->name('tech.dashboard');
});



