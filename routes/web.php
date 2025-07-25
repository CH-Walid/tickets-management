<?php

use Illuminate\Support\Facades\Route;
use App\Enums\RolesEnum;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\ChefTicketController;
use App\Http\Controllers\TechnicienController;
use App\Http\Controllers\admin\TicketController as AdminTicketController;
use App\Http\Controllers\admin\TechnicienController as AdminTechnicienController;
use App\Http\Controllers\admin\UserSimpleController;
use App\Http\Controllers\admin\TicketAdminController;
use App\Http\Controllers\admin\ServiceController;
use App\Http\Controllers\admin\CategorieController;
use App\Http\Controllers\admin\StatistiquesController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\tech\TicketTechController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==== Authentification ====
Route::middleware(['guest'])->group(function () {
    Route::get('/techniciens/definir-mot-de-passe/{token}', [TechnicienController::class, 'showPasswordForm'])->name('technicien.password.form');
    Route::post('/techniciens/definir-mot-de-passe', [TechnicienController::class, 'storePassword'])->name('technicien.password.store');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ==== Redirection d’accueil selon rôle ====
Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            RolesEnum::ADMIN->value => redirect()->route('admin.dashboard'),
            RolesEnum::USER_SIMPLE->value => redirect()->route('user.dashboard'),
            RolesEnum::TECHNICIEN->value => redirect()->route('tech.dashboard'),
            RolesEnum::CHEF_TECHNICIEN->value => redirect()->route('chef.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
})->name('home');

// ==== Routes protégées ====
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ===== Profil utilisateur =====
    Route::prefix('profile')->name('user.profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/upload-image', [ProfileController::class, 'uploadImage'])->name('upload.image');
        Route::delete('/image', [ProfileController::class, 'deleteImage'])->name('delete-image');
    });

    // ===== Utilisateur simple =====
    Route::middleware(['role:' . RolesEnum::USER_SIMPLE->value])->group(function () {
        Route::get('/user/dashboard', [IncidentController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/user/ticket', [IncidentController::class, 'create'])->name('user.ticket');
        Route::post('/user/ticket', [IncidentController::class, 'store'])->name('incident.store');
        Route::get('/tickets/all', [IncidentController::class, 'allTickets'])->name('tickets.all');
        Route::get('/tickets/{id}/edit', [IncidentController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{id}', [IncidentController::class, 'update'])->name('incident.update');
        Route::delete('/tickets/{id}', [IncidentController::class, 'destroy'])->name('tickets.destroy');
    });

    // ===== Admin =====
    Route::middleware(['role:' . RolesEnum::ADMIN->value])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminTicketController::class, 'dashboard'])->name('dashboard');

        Route::get('/tickets', fn() => view('admin.tickets'))->name('tickets');
        Route::get('/search', [AdminTicketController::class, 'search'])->name('search');
        Route::get('/profil', [AdminTicketController::class, 'profil'])->name('profil');
        Route::get('/profil/edit', [AdminTicketController::class, 'editProfil'])->name('profil.edit');
        Route::post('/profil/edit', [AdminTicketController::class, 'updateProfil'])->name('profil.update');
        Route::get('/parametres', [AdminTicketController::class, 'parametres'])->name('parametres');
        Route::match(['get', 'post'], '/password', [AdminTicketController::class, 'password'])->name('password');

        Route::get('/techniciens/export', [AdminTechnicienController::class, 'export'])->name('techniciens.export');
        Route::resource('/techniciens', AdminTechnicienController::class, ['as' => 'techniciens']);

        Route::resource('utilisateurs', UserSimpleController::class);
        Route::get('utilisateurs/export', [UserSimpleController::class, 'export'])->name('utilisateurs.export');

        Route::resource('tickets', TicketAdminController::class);
        Route::get('tickets/export', [TicketAdminController::class, 'export'])->name('tickets.export');

        Route::resource('services', ServiceController::class);
        Route::get('services/export', [ServiceController::class, 'export'])->name('services.export');

        Route::get('parametrage', [ServiceController::class, 'index'])->name('parametrage');

        Route::resource('categories', CategorieController::class)->except(['show', 'index']);
        Route::get('categories/export', [CategorieController::class, 'export'])->name('categories.export');

        Route::get('statistiques', [StatistiquesController::class, 'index'])->name('statistiques.index');

        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

        Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
        Route::post('messages', [MessageController::class, 'store'])->name('messages.store');
        Route::post('messages/{id}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    });

    // ===== Chef Technicien =====
    Route::middleware(['role:' . RolesEnum::CHEF_TECHNICIEN->value])->prefix('chef')->name('chef.')->group(function () {
        Route::get('/dashboard', [ChefTicketController::class, 'dashboard'])->name('dashboard');
        Route::get('/tickets-all', [ChefTicketController::class, 'ticketsAll'])->name('tickets.all');
        Route::get('/tickets/{id}/edit', [ChefTicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{id}', [ChefTicketController::class, 'update'])->name('tickets.update');
        Route::delete('/tickets/{id}', [ChefTicketController::class, 'destroy'])->name('tickets.destroy');
        Route::post('/tickets/{id}/assign', [ChefTicketController::class, 'assignTechnicien'])->name('tickets.assign');
        Route::get('/tickets/export', [ChefTicketController::class, 'export'])->name('tickets.export');
        Route::get('/techniciens', [ChefTicketController::class, 'listeTechniciens'])->name('techniciens');
        Route::get('/tickets/all', [ChefTicketController::class, 'allTickets']);

        Route::prefix('techniciens')->name('techniciens.')->group(function () {
            Route::get('/', [TechnicienController::class, 'index'])->name('index');
            Route::get('/create', [ChefTicketController::class, 'create'])->name('create');
            Route::post('/', [ChefTicketController::class, 'storeTechnicien'])->name('store');
            Route::get('/{technicien}/edit', [TechnicienController::class, 'edit'])->name('edit');
            Route::put('/{technicien}', [TechnicienController::class, 'update'])->name('update');
            Route::delete('/{technicien}', [TechnicienController::class, 'destroy'])->name('destroy');
        });

        Route::get('/techniciens/export/pdf', [TechnicienController::class, 'exportPdf'])->name('techniciens.export.pdf');
    });

    // ===== Technicien =====
    Route::middleware(['role:' . RolesEnum::TECHNICIEN->value])->prefix('tech')->name('tech.')->group(function () {
        Route::get('/dashboard', fn() => view("tech.dashboard"))->name('dashboard');

        Route::get('/tickets', [TicketTechController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{id}', [TicketTechController::class, 'show'])->name('tickets.show');
        Route::get('/tickets/{id}/edit', [TicketTechController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{id}', [TicketTechController::class, 'update'])->name('tickets.update');

        Route::post('/tickets/{id}/commenter', [TicketTechController::class, 'commenter'])->name('tickets.commenter');
        Route::put('/commentaires/{id}', [TicketTechController::class, 'updateCommentaire'])->name('commentaires.update');
        Route::delete('/commentaires/{id}', [TicketTechController::class, 'deleteCommentaire'])->name('commentaires.destroy');
    });

    // Tous rôles authentifiés : voir un ticket
    Route::get('/tickets/{id}', [ChefTicketController::class, 'show'])->name('tickets.show');
});
