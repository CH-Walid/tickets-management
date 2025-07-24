<?php

use App\Enums\RolesEnum;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\tech\TicketTechController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==== Authentification ====
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ==== Redirection page d’accueil ====
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// ==== Dashboards protégés ====
Route::middleware(['auth'])->group(function () {

    // Dashboard user simple
    Route::middleware(['role:' . RolesEnum::USER_SIMPLE->value])->group(function () {
        Route::get('/user/dashboard', function () {
            return 'Bienvenue sur le dashboard utilisateur simple.';
        })->name('user.dashboard');
    });

    // Dashboard admin
    Route::middleware(['role:' . RolesEnum::ADMIN->value])->group(function () {
        Route::get('/admin/dashboard', function () {
            return 'Bienvenue sur le dashboard administrateur.';
        })->name('admin.dashboard');
    });

    // Dashboard chef technicien
    Route::middleware(['role:' . RolesEnum::CHEF_TECHNICIEN->value])->group(function () {
        Route::get('/chef/dashboard', function () {
            return 'Bienvenue sur le dashboard chef.';
        })->name('chef.dashboard');
    });

    // ==== Bloc Technicien ====
    Route::middleware(['role:' . RolesEnum::TECHNICIEN->value])->group(function () {

        // Dashboard technicien
        Route::get('/tech/dashboard', function () {
            return 'Bienvenue sur le dashboard technicien.';
        })->name('tech.dashboard');

        // Gestion des tickets
        Route::get('/tech/tickets', [TicketTechController::class, 'index'])->name('tickets.index');
        Route::get('/tech/tickets/{id}', [TicketTechController::class, 'show'])->name('tickets.show');
        Route::get('/tech/tickets/{id}/edit', [TicketTechController::class, 'edit'])->name('tickets.edit');
        Route::put('/tech/tickets/{id}', [TicketTechController::class, 'update'])->name('tickets.update');

        // ✅ Route pour commenter un ticket (corrigée)
        Route::post('/tech/tickets/{id}/commenter', [TicketTechController::class, 'commenter'])->name('tickets.commenter');
    });
});
