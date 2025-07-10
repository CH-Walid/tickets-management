<?php

use App\Enums\RolesEnum;
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

    Route::middleware(['role:'.RolesEnum::USER_SIMPLE->value])->group(function () {
        Route::get('/user/dashboard', function () {
            return 'Bienvenue sur le dashboard utilisateur simple.';
        })->name('user.dashboard');
    });


    Route::middleware(['role:'.RolesEnum::ADMIN->value])->group(function () {
        Route::get('/admin/dashboard', function () {
            return 'Bienvenue sur le dashboard administrateur.';
        })->name('admin.dashboard');
    });


    Route::middleware(['role:'.RolesEnum::CHEF_TECHNICIEN->value])->group(function () {
        Route::get('/chef/dashboard', function () {
            return 'Bienvenue sur le dashboard chef.';
        })->name('chef.dashboard');
    });


    Route::middleware(['role:'.RolesEnum::TECHNICIEN->value])->group(function () {
        Route::get('/tech/dashboard', function () {
            return 'Bienvenue sur le dashboard technicien.';
        })->name('tech.dashboard');
    });

});



