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

});

// Route logout globale (à la fin du fichier, hors groupe)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==== Dashboards protégés ====

// Dashboard de l'utilisateur simple
Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:'.RolesEnum::USER_SIMPLE->value])->group(function () {
        Route::get('/user/dashboard', function () {
            return 'Bienvenue sur le dashboard utilisateur simple.';
        })->name('user.dashboard');
    });


    Route::middleware(['role:'.RolesEnum::ADMIN->value])->group(function () {
        Route::get('/admin/dashboard', [\App\Http\Controllers\admin\TicketController::class, 'dashboard'])->name('admin.dashboard');

        Route::get('/admin/tickets', function () {
            return view('admin.tickets');
        })->name('admin.tickets');

        // Gestion des techniciens
        Route::get('/admin/techniciens/export', [\App\Http\Controllers\admin\TechnicienController::class, 'export'])->name('admin.techniciens.export');
        Route::resource('/admin/techniciens', \App\Http\Controllers\admin\TechnicienController::class, [
            'as' => 'admin.techniciens'
        ]);


        Route::get('/admin/search', [\App\Http\Controllers\admin\TicketController::class, 'search'])->name('admin.search');
        Route::get('/admin/profil', [\App\Http\Controllers\admin\TicketController::class, 'profil'])->name('admin.profil');
        Route::get('/admin/profil/edit', [\App\Http\Controllers\admin\TicketController::class, 'editProfil'])->name('admin.profil.edit');
        Route::post('/admin/profil/edit', [\App\Http\Controllers\admin\TicketController::class, 'updateProfil'])->name('admin.profil.update');
        Route::get('/admin/parametres', [\App\Http\Controllers\admin\TicketController::class, 'parametres'])->name('admin.parametres');
        Route::match(['get', 'post'], '/admin/password', [\App\Http\Controllers\admin\TicketController::class, 'password'])->name('admin.password');
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

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/utilisateurs', [\App\Http\Controllers\admin\UserSimpleController::class, 'index'])->name('utilisateurs.index');
    Route::get('/utilisateurs/export', [\App\Http\Controllers\admin\UserSimpleController::class, 'export'])->name('utilisateurs.export');
    Route::get('/utilisateurs/create', [\App\Http\Controllers\admin\UserSimpleController::class, 'create'])->name('utilisateurs.create');
    Route::post('/utilisateurs', [\App\Http\Controllers\admin\UserSimpleController::class, 'store'])->name('utilisateurs.store');
    Route::get('/utilisateurs/{id}', [\App\Http\Controllers\admin\UserSimpleController::class, 'show'])->name('utilisateurs.show');
    Route::get('/utilisateurs/{id}/edit', [\App\Http\Controllers\admin\UserSimpleController::class, 'edit'])->name('utilisateurs.edit');
    Route::put('/utilisateurs/{id}', [\App\Http\Controllers\admin\UserSimpleController::class, 'update'])->name('utilisateurs.update');
    Route::delete('/utilisateurs/{id}', [\App\Http\Controllers\admin\UserSimpleController::class, 'destroy'])->name('utilisateurs.destroy');
    Route::get('/tickets', [\App\Http\Controllers\admin\TicketAdminController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/export', [\App\Http\Controllers\admin\TicketAdminController::class, 'export'])->name('tickets.export');
    Route::get('/tickets/create', [\App\Http\Controllers\admin\TicketAdminController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [\App\Http\Controllers\admin\TicketAdminController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{id}', [\App\Http\Controllers\admin\TicketAdminController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{id}/edit', [\App\Http\Controllers\admin\TicketAdminController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{id}', [\App\Http\Controllers\admin\TicketAdminController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{id}', [\App\Http\Controllers\admin\TicketAdminController::class, 'destroy'])->name('tickets.destroy');
    Route::resource('services', App\Http\Controllers\admin\ServiceController::class);
    Route::get('services/export', [App\Http\Controllers\admin\ServiceController::class, 'export'])->name('admin.services.export');
    Route::get('parametrage', [App\Http\Controllers\admin\ServiceController::class, 'index'])->name('parametrage');
    Route::resource('categories', App\Http\Controllers\admin\CategorieController::class)->except(['show', 'index']);
    Route::get('categories/export', [App\Http\Controllers\admin\CategorieController::class, 'export'])->name('admin.categories.export');
    Route::get('statistiques', [App\Http\Controllers\admin\StatistiquesController::class, 'index'])->name('admin.statistiques.index');
    Route::get('parametres', [\App\Http\Controllers\admin\TicketController::class, 'parametres'])->name('admin.parametres');
    Route::post('parametres', [\App\Http\Controllers\admin\TicketController::class, 'updateParametres'])->name('admin.parametres.update');
});

// Notifications
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::post('notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
    Route::delete('notifications/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('admin.notifications.destroy');

    // Chat direct admin/chef technicien
    Route::get('messages', [\App\Http\Controllers\Admin\MessageController::class, 'index'])->name('admin.messages.index');
    Route::post('messages', [\App\Http\Controllers\Admin\MessageController::class, 'store'])->name('admin.messages.store');
    Route::post('messages/{id}/read', [\App\Http\Controllers\Admin\MessageController::class, 'markAsRead'])->name('admin.messages.read');
});


Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        return match ($role) {
            RolesEnum::ADMIN->value => redirect()->route('admin.dashboard'),
            RolesEnum::USER_SIMPLE->value => redirect()->route('user.dashboard'),
            RolesEnum::TECHNICIEN->value => redirect()->route('tech.dashboard'),
            RolesEnum::CHEF_TECHNICIEN->value => redirect()->route('chef.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
})->name('home');
