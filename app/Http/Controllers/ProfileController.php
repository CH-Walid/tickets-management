<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Afficher le profil de l'utilisateur connecté
     */
    public function show()
    {
        $user = Auth::user();
        
        // Charger les relations nécessaires selon le rôle
        switch ($user->role) {
            case 'user':
                $user->load('userSimple.service');
                break;
            case 'tech':
                $user->load('technicien.service');
                break;
            case 'chef':
                $user->load('chefTechnicien');
                break;
            case 'admin':
                $user->load('admin');
                break;
        }
        
        return view('user.profile.show', compact('user'));
    }

    /**
     * Afficher le formulaire d'édition du profil
     */
    public function edit()
    {
        $user = Auth::user();
        
        // Charger les relations nécessaires selon le rôle
        switch ($user->role) {
            case 'user':
                $user->load('userSimple.service');
                break;
            case 'tech':
                $user->load('technicien.service');
                break;
            case 'chef':
                $user->load('chefTechnicien');
                break;
            case 'admin':
                $user->load('admin');
                break;
        }
        
        return view('user.profile.edit', compact('user'));
    }

    /**
     * Mettre à jour le profil de l'utilisateur
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validation des données (uniquement les champs demandés)
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'phone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        try {
            // Gestion du mot de passe
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']); // Ne pas mettre à jour le mot de passe s'il est vide
            }

            // Mettre à jour l'utilisateur
            $user->update($validated);

            return redirect()->route('user.profile.show')
                ->with('success', 'Votre profil a été mis à jour avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du profil: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de votre profil. Veuillez réessayer.');
        }
    }

    /**
     * Gérer l'upload de la photo de profil via AJAX
     */
    public function uploadImage(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ], [
            'image.required' => 'Veuillez sélectionner une image.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format jpeg, png, jpg ou gif.',
            'image.max' => 'L\'image ne peut pas dépasser 2MB.',
        ]);

        try {
            // Supprimer l'ancienne image si elle existe
            if ($user->img && Storage::disk('public')->exists($user->img)) {
                Storage::disk('public')->delete($user->img);
            }
            
            // Stocker la nouvelle image
            $imagePath = $request->file('image')->store('profile-images', 'public');
            $user->update(['img' => $imagePath]);
            

            return response()->json([
                'success' => true,
                'message' => 'Photo de profil mise à jour avec succès.',
                'image_url' => $user->profile_image_url // Utilise l'accesseur pour l'URL complète
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'upload de l\'image de profil: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du téléchargement de l\'image.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer la photo de profil
     */
    public function deleteImage()
    {
        $user = Auth::user();
        
        try {
            if ($user->img && Storage::disk('public')->exists($user->img)) {
                Storage::disk('public')->delete($user->img);
                $user->update(['img' => null]);
                
                return redirect()->back()
                    ->with('success', 'Photo de profil supprimée avec succès.');
            }
            
            return redirect()->back()
                ->with('info', 'Aucune photo de profil à supprimer.');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'image de profil: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression de la photo.');
        }
    }
}
