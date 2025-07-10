<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserSimple;
use App\Models\Service;

class AuthController extends Controller
{
    // Affiche le formulaire de connexion
    public function showLoginForm() {
        return view('login');
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

            // Redirection personnalisée selon le rôle
            if ($user->role === 'user') {
                return redirect()->route('user.dashboard');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'chef') {
                return redirect()->route('chef.dashboard');
            } elseif ($user->role === 'tech') {
                return redirect()->route('tech.dashboard');
            }

            return redirect('/'); // au cas où
        }

        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.',
        ])->withInput();
    }

    // Affiche le formulaire d'inscription
    public function showRegisterForm() {
        $services = Service::all(); 
        return view('register', compact('services'));
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

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // user_simple par convention
        ]);

        UserSimple::create([
            'id' => $user->id,
            'service_id' => $request->service_id,
        ]);

        // Redirige vers login (pas d'auth automatique)
        return redirect()->route('login')->with('success', 'Inscription réussie. Veuillez vous connecter.');
    }

    // Déconnexion (optionnel)
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
