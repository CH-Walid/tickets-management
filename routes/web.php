<?php

use App\Enums\RolesEnum;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\ChefTicketController;
use App\Http\Controllers\TechnicienController;






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

    // Afficher le formulaire pour définir le mot de passe (avec token)
Route::get('/techniciens/definir-mot-de-passe/{token}', [TechnicienController::class, 'showPasswordForm'])->name('technicien.password.form');

// Traiter la soumission du formulaire pour définir le mot de passe
Route::post('/techniciens/definir-mot-de-passe', [TechnicienController::class, 'storePassword'])->name('technicien.password.store');


    // Formulaire de connexion
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

    // Traitement de connexion
    Route::post('/login', [AuthController::class, 'login']);

    // Formulaire d'inscription (user_simple uniquement)
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    // Traitement de l'inscription
    Route::post('/register', [AuthController::class, 'register']);

});

// ==== Dashboards protégés ====

// Dashboard de l'utilisateur simple
Route::middleware(['auth'])->group(function () {
    // NOUVEAU BLOC DE ROUTES POUR LE PROFIL
    Route::prefix('profile')->name('user.profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/upload-image', [ProfileController::class, 'uploadImage'])->name('upload.image'); // Nouvelle route pour l'upload AJAX
        Route::delete('/image', [ProfileController::class, 'deleteImage'])->name('delete-image');
    });

    //deconnexion 
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['role:'.RolesEnum::USER_SIMPLE->value])->group(function () {
        
        Route::get('/user/ticket', [IncidentController::class, 'create'])->name('user.ticket');
        Route::post('/user/ticket', [IncidentController::class, 'store'])->name('incident.store');

        Route::get('/user/dashboard', [IncidentController::class, 'dashboard'])->name('user.dashboard');

        // Affichage du formulaire d'édition
        Route::get('/tickets/{id}/edit', [IncidentController::class, 'edit'])->name('tickets.edit');

        // Soumission du formulaire d’édition (update)
        Route::put('/tickets/{id}', [IncidentController::class, 'update'])->name('incident.update');
        Route::delete('/tickets/{id}', [IncidentController::class, 'destroy'])->name('tickets.destroy');

        Route::get('/tickets/all', [IncidentController::class, 'allTickets'])->name('tickets.all');
        
        
        
 });


   

    Route::middleware(['role:'.RolesEnum::ADMIN->value])->group(function () {
        Route::get('/admin/dashboard', function () {
            return 'Bienvenue sur le dashboard administrateur.';
        })->name('admin.dashboard');
    });


    Route::middleware(['role:'.RolesEnum::CHEF_TECHNICIEN->value])->group(function () {
        // Route dashboard : affiche tickets récents (sans pagination)
    Route::get('/chef/dashboard', [ChefTicketController::class, 'dashboard'])->name('chef.dashboard');

    // Route tickets-all : liste complète des tickets paginés + recherche
    Route::get('/chef/tickets-all', [ChefTicketController::class, 'ticketsAll'])->name('chef.tickets.all');

    Route::get('/chef/tickets/{id}/edit', [ChefTicketController::class, 'edit'])->name('chef.tickets.edit');

    // Soumission du formulaire (update)
    Route::put('/chef/tickets/{id}', [ChefTicketController::class, 'update'])->name('chef.tickets.update');

    // Suppression d'un ticket
    Route::delete('/chef/tickets/{id}', [ChefTicketController::class, 'destroy'])->name('chef.tickets.destroy');

     Route::get('/chef/techniciens', [ChefTicketController::class, 'listeTechniciens'])->name('chef.techniciens');
     Route::get('/chef/tickets/all', [ChefTicketController::class, 'allTickets'])->name('chef.tickets.all');
     Route::post('/chef/tickets/{id}/assign', [ChefTicketController::class, 'assignTechnicien'])->name('chef.tickets.assign');

    
    Route::get('/chef/tickets/export', [ChefTicketController::class, 'export'])->name('chef.tickets.export');

    Route::get('/techniciens/export/pdf', [\App\Http\Controllers\TechnicienController::class, 'exportPdf'])->name('techniciens.export.pdf');





    Route::prefix('chef/techniciens')->name('chef.techniciens.')->middleware(['auth', 'role:chef_technicien'])->group(function () {
    Route::get('/', [TechnicienController::class, 'index'])->name('index');
    Route::get('/{technicien}/edit', [TechnicienController::class, 'edit'])->name('edit');
    Route::put('/{technicien}', [TechnicienController::class, 'update'])->name('update');
    Route::delete('/{technicien}', [TechnicienController::class, 'destroy'])->name('destroy');
    // Afficher le formulaire de création d’un technicien
    Route::get('/create', [ChefTicketController::class, 'create'])->name('create'); 
});


// Soumettre le formulaire pour créer un technicien
Route::post('/techniciens', [ChefTicketController::class, 'storeTechnicien'])->name('technicien.store');



});
    


    Route::middleware(['role:'.RolesEnum::TECHNICIEN->value])->group(function () {
        Route::get('/tech/dashboard', function () {
            return view("tech.dashboard");
        })->name('tech.dashboard');
        
    });
   

Route::get('/tickets/{id}', [ChefTicketController::class, 'show'])->name('tickets.show');


});


Route::get('/', function () {
    return redirect()->route('login');
})->name('home');
