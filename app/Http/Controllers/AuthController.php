<?php

namespace App\Http\Controllers;

use App\Enums\RolesEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserSimple;
use App\Models\Service;

class AuthController extends Controller
{

    protected $redirects = [
        'admin' => 'admin.dashboard',
        'chef_technicien' => 'chef.dashboard',
        'technicien' => 'tech.dashboard',
        'user_simple' => 'user.dashboard',
    ];

    // Affiche le formulaire de connexion
    public function showLoginForm() {
        return view('auth.login');
    }

    // Traitement de la connexion
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $user->last_login_at = now();
            $user->save();

            // Check if user role has a specific dashboard, otherwise redirect to home
            if (isset($this->redirects[$user->role])) {
                return redirect()->route($this->redirects[$user->role]);
            } else {
                return redirect()->route('home');
            }
        }

        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.',
        ])->withInput();
    }

    // Affiche le formulaire d'inscription
    public function showRegisterForm() {
        $services = Service::all();
        return view('auth.register', compact('services'));
    }

    // Traitement de l'inscription
    public function register(Request $request) {
        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'service_id' => 'required|exists:services,id',
        ]);

        User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])->userSimple()->create([
            'service_id' => $request->service_id,
        ]);

        // Redirige vers login (pas d'auth automatique)
        return redirect()->route('login')->with('success', 'Inscription réussie. Veuillez vous connecter.');
    }

    // Déconnexion
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}