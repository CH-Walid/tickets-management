<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Technicien;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     * Crée automatiquement l'entrée liée selon le rôle.
     */
    public function created(User $user)
    {
        // Pour technicien : nécessite service_id dans la requête
        if ($user->role === 'technicien') {
            if (!$user->technicien) {
                $service_id = request('service_id');
                if ($service_id) {
                    $user->technicien()->create([
                        'id' => $user->id,
                        'service_id' => $service_id,
                    ]);
                } else {
                    Log::warning('Création technicien sans service_id', ['user_id' => $user->id]);
                }
            }
        }
        // Pour admin (exemple)
        if ($user->role === 'admin' && method_exists($user, 'admin')) {
            if (!$user->admin) {
                $user->admin()->create(['id' => $user->id]);
            }
        }
        // Pour chef_technicien (exemple)
        if ($user->role === 'chef_technicien' && method_exists($user, 'chefTechnicien')) {
            if (!$user->chefTechnicien) {
                $user->chefTechnicien()->create(['id' => $user->id]);
            }
        }
        // Ajoute d'autres rôles ici si besoin
    }
}
