<?php

namespace App\Http\Controllers;

use App\Models\Technicien;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Pdf;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TechnicienController extends Controller
{
    // Liste des techniciens
   public function index(Request $request)
    {
        $search = $request->input('search');

    $query = Technicien::with(['user', 'service']);

    if ($search) {
        $query->whereHas('user', function($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('prenom', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        })->orWhereHas('service', function($q) use ($search) {
            $q->where('titre', 'like', "%{$search}%");
        });
    }

    $techniciens = $query->orderBy('id', 'desc')->paginate(3);

    return view('chef.techniciens.index', compact('techniciens'));
    }

    // Formulaire édition technicien
    public function edit(Technicien $technicien)
    {
        $services = Service::all();
        return view('chef.techniciens.edit', compact('technicien', 'services'));
    }

    // Mise à jour du technicien
    public function update(Request $request, Technicien $technicien)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => [
        'required',
        'email',
        'max:255',
        
        Rule::unique('users')->ignore($technicien->user->id),
                        ],
            'service_id' => 'nullable|exists:services,id',
        ]);

        $user = $technicien->user;
        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'];
        $user->email = $validated['email'];
        $user->save();

        $technicien->service_id = $validated['service_id'];
        $technicien->save();

        return redirect()->route('chef.techniciens.index')->with('success', 'Technicien mis à jour.');
    }

    // Suppression technicien (et utilisateur lié)
    public function destroy(Technicien $technicien)
    {
        $technicien->user()->delete();
        return redirect()->route('chef.techniciens.index')->with('success', 'Technicien supprimé.');
    }
   public function exportPdf()
{
    $techniciens = Technicien::with(['user', 'service'])->get();

    $pdf = PDF::loadView('chef.techniciens.techniciens_export_pdf', compact('techniciens'))
              ->setPaper('A4', 'landscape');

    return $pdf->download('techniciens_' . now()->format('Ymd_His') . '.pdf');
}

 public function showPasswordForm($token)
    {
       
        $token = trim($token); 
        $user = User::where('password_token', $token)->firstOrFail();
        return view('chef.techniciens.password-form', compact('token'));
    }

    public function storePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::where('password_token', $request->token)->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->password_token = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Mot de passe défini avec succès.');
    }
}
