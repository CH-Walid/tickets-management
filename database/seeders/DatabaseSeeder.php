<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\RolesEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        
        
        \App\Models\Service::create(['titre' => 'Service RH']);
        \App\Models\Service::create(['titre' => 'Service COM']);

        \App\Models\Categorie::create(['titre' => 'Réseau']);
        \App\Models\Categorie::create(['titre' => 'Matériel']);
        \App\Models\Categorie::create(['titre' => 'Logiciel']);
        \App\Models\Categorie::create(['titre' => 'Sécurité']);
        \App\Models\Categorie::create(['titre' => 'Comptabilité']);
        \App\Models\Categorie::create(['titre' => 'Ressources humaines']);
        \App\Models\Categorie::create(['titre' => 'Bureau / Admin']);
        \App\Models\Categorie::create(['titre' => 'Demande d’accès']);
        \App\Models\Categorie::create(['titre' => 'Développement']);
        \App\Models\Categorie::create(['titre' => 'Maintenance']);
        \App\Models\Categorie::create(['titre' => 'Téléphonie']);
        \App\Models\Categorie::create(['titre' => 'Autre', 'is_official' => false]);             

        \App\Models\User::factory()->create([
            'nom' => 'User',
            'prenom' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('password')
        ])->userSimple()->create([
            'service_id' => 2,
        ]);

        \App\Models\User::create(
            [
                'nom' => 'user',
                'prenom' => 'user',
                'email' => 'p',
                'password' => Hash::make("123456")
            ]
        )->userSimple()->create([
            'service_id' => 2,
        ]);

        \App\Models\User::create(
            [
                'nom' => 'admin',
                'prenom' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make("123456"),
                'role' => RolesEnum::ADMIN->value
            ]
        )->admin()->create(); 

        \App\Models\User::create(
            [
            'nom' => 'tech',
            'prenom' => 'tech',
            'email' => 'tech@gmail.com',
            'password' => Hash::make("123456"),
            'role' => RolesEnum::TECHNICIEN->value
            ]
        )->technicien()->create([
            'service_id' => 1,
        ]);

        // TECH
        \App\Models\User::create(
            [
            'nom' => 'anass',
            'prenom' => 'benheddane',
            'email' => 'a.benheddane@gmail.com',
            'password' => Hash::make("a.benheddane@gmail.com"),
            'role' => RolesEnum::TECHNICIEN->value
            ]
        )->technicien()->create([
            'service_id' => 1,
        ]);

        // ADMIN
        \App\Models\User::create(
            [
                'nom' => 'chaimae',
                'prenom' => 'chaimae',
                'email' => 'chaimae@gmail.com',
                'password' => Hash::make("chaimae@gmail.com"),
                'role' => RolesEnum::ADMIN->value
            ]
        )->admin()->create();

        // CHEF TECHNICIEN
        \App\Models\User::create([
            'nom' => 'dounia',
            'prenom' => 'dounia',
            'email' => 'dounia@gmail.com',
            'password' => Hash::make("dounia@gmail.com"),
            'role' => RolesEnum::CHEF_TECHNICIEN->value
        ])->chefTechnicien()->create(); 

        // USER SIMPLE
        \App\Models\User::create(
            [
                'nom' => 'noubaiba',
                'prenom' => 'noubaiba',
                'email' => 'noubaiba@gmail.com',
                'password' => Hash::make("noubaiba@gmail.com")
            ]
        )->userSimple()->create([
            'service_id' => 1,
        ]);
        

    }
}
