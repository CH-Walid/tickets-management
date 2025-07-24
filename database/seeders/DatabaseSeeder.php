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
        
        
        \App\Models\Categorie::create(['titre' => 'Panne matérielle']);
        \App\Models\Categorie::create(['titre' => 'Problème logiciel']);
        \App\Models\Categorie::create(['titre' => 'Demande d accès']);
        \App\Models\Categorie::create(['titre' => 'Problème réseau']);
        \App\Models\Categorie::create(['titre' => 'Autre']);
        \App\Models\Service::create(['titre' => 'Service RH']);
        \App\Models\Service::create(['titre' => 'Service COM']);

        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'nom' => 'User',
            'prenom' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('password')
        ]);

        \App\Models\User::create(
            [
                'nom' => 'user',
                'prenom' => 'user',
                'email' => 'user@gmail.com',
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
        );

        \App\Models\User::create(
            [
                'nom' => 'chef',
                'prenom' => 'chef',
                'email' => 'chef@gmail.com',
                'password' => Hash::make("123456"),
                'role' => RolesEnum::CHEF_TECHNICIEN->value
            ]
        );

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

    }
}
