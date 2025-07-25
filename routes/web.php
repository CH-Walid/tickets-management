<?php

use App\Enums\RolesEnum;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\ChefTicketController;
use App\Http\Controllers\TechnicienController;
use App\Http\Controllers\tech\TicketTechController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==== Authentification ====
Route::middleware(['guest'])->group(function () {

    // Afficher le formulaire pour définir le mot de passe (avec token)
    Route::get('/techniciens/definir-mot-de-passe/{token}', [TechnicienController::class, 'showPasswordForm'])->name('technicien.password.form');

    // Traiter la soumission du formulaire pour définir le mot de passe
    Route::post('/techniciens/definir-mot-de-passe', [TechnicienController::class, 'storePassword'])->name('technicien.password.store');

    // Formulaire de connexion
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
});

// ==== Redirection page d’accueil ====
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// ==== Dashboards protégés ====
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Bloc profil utilisateur
    Route::prefix('profile')->name('user.profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/upload-image', [ProfileController::class, 'uploadImage'])->name('upload.image');
        Route::delete('/image', [ProfileController::class, 'deleteImage'])->name('delete-image');
    });

    // Dashboard utilisateur simple
    Route::middleware(['role:' . RolesEnum::USER_SIMPLE->value])->group(function () {
        Route::get('/user/ticket', [IncidentController::class, 'create'])->name('user.ticket');
        Route::post('/user/ticket', [IncidentController::class, 'store'])->name('incident.store');
        Route::get('/user/dashboard', [IncidentController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/tickets/{id}/edit', [IncidentController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{id}', [IncidentController::class, 'update'])->name('incident.update');
        Route::delete('/tickets/{id}', [IncidentController::class, 'destroy'])->name('tickets.destroy');
        Route::get('/tickets/all', [IncidentController::class, 'allTickets'])->name('tickets.all');
    });

    // Dashboard admin
    Route::middleware(['role:' . RolesEnum::ADMIN->value])->group(function () {
        Route::get('/admin/dashboard', function () {
            return 'Bienvenue sur le dashboard administrateur.';
        })->name('admin.dashboard');
    });

    // Dashboard chef technicien
    Route::middleware(['role:' . RolesEnum::CHEF_TECHNICIEN->value])->group(function () {
        Route::get('/chef/dashboard', [ChefTicketController::class, 'dashboard'])->name('chef.dashboard');
        Route::get('/chef/tickets-all', [ChefTicketController::class, 'ticketsAll'])->name('chef.tickets.all');
        Route::get('/chef/tickets/{id}/edit', [ChefTicketController::class, 'edit'])->name('chef.tickets.edit');
        Route::put('/chef/tickets/{id}', [ChefTicketController::class, 'update'])->name('chef.tickets.update');
        Route::delete('/chef/tickets/{id}', [ChefTicketController::class, 'destroy'])->name('chef.tickets.destroy');
        Route::get('/chef/techniciens', [ChefTicketController::class, 'listeTechniciens'])->name('chef.techniciens');
        Route::get('/chef/tickets/all', [ChefTicketController::class, 'allTickets'])->name('chef.tickets.all');
        Route::post('/chef/tickets/{id}/assign', [ChefTicketController::class, 'assignTechnicien'])->name('chef.tickets.assign');
        Route::get('/chef/tickets/export', [ChefTicketController::class, 'export'])->name('chef.tickets.export');
        Route::get('/techniciens/export/pdf', [TechnicienController::class, 'exportPdf'])->name('techniciens.export.pdf');

        Route::prefix('chef/techniciens')->name('chef.techniciens.')->group(function () {
            Route::get('/', [TechnicienController::class, 'index'])->name('index');
            Route::get('/{technicien}/edit', [TechnicienController::class, 'edit'])->name('edit');
            Route::put('/{technicien}', [TechnicienController::class, 'update'])->name('update');
            Route::delete('/{technicien}', [TechnicienController::class, 'destroy'])->name('destroy');
            Route::get('/create', [ChefTicketController::class, 'create'])->name('create');
        });

        Route::post('/techniciens', [ChefTicketController::class, 'storeTechnicien'])->name('technicien.store');
    });

    // Bloc technicien
    Route::middleware(['role:' . RolesEnum::TECHNICIEN->value])->group(function () {
        Route::get('/tech/dashboard', function () {
            return view("tech.dashboard");
        })->name('tech.dashboard');

        Route::get('/tech/tickets', [TicketTechController::class, 'index'])->name('tickets.index');
        Route::get('/tech/tickets/{id}', [TicketTechController::class, 'show'])->name('tech.tickets.show');
        Route::get('/tech/tickets/{id}/edit', [TicketTechController::class, 'edit'])->name('tickets.edit');
        Route::put('/tech/tickets/{id}', [TicketTechController::class, 'update'])->name('tickets.update');

        Route::post('/tech/tickets/{id}/commenter', [TicketTechController::class, 'commenter'])->name('tickets.commenter');
        Route::put('/tech/commentaires/{id}', [TicketTechController::class, 'updateCommentaire'])->name('commentaires.update');
        Route::delete('/tech/commentaires/{id}', [TicketTechController::class, 'deleteCommentaire'])->name('commentaires.destroy');
    });

    // Accessible à tous les rôles authentifiés (par exemple, voir ticket)
    Route::get('/tickets/{id}', [ChefTicketController::class, 'show'])->name('tickets.show');
});
