<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'img',
        'phone',
        'role',
        'password_token',
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    /*protected $casts = [
        'email_verified_at' => 'datetime',
    ]; */

    public function admin()
    {
        return $this->hasOne(Admin::class, 'id');
    }

    public function chefTechnicien()
    {
        return $this->hasOne(ChefTechnicien::class, 'id');
    }

    public function technicien()
    {
        return $this->hasOne(Technicien::class, 'id');
    }

    public function userSimple()
    {
        return $this->hasOne(UserSimple::class, 'id');
    }



/**
     * Obtenir l'URL complète de l'image de profil ou une image d'initiales SVG.
     */
    public function getProfileImageUrlAttribute()
    {

        if ($this->img) {
            return Storage::url($this->img);
        }
        
        // Générer une image SVG avec les initiales
        $initials = strtoupper(substr($this->prenom, 0, 1) . substr($this->nom, 0, 1));
        $bgColor = '#4A90E2'; // Couleur de fond bleue
        $textColor = '#FFFFFF'; // Couleur du texte blanc
        $fontSize = 40; // Taille de la police

        $svg = '<svg width="96" height="96" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg">';
        $svg .= '<rect width="96" height="96" fill="' . $bgColor . '"/>';
        $svg .= '<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="' . $textColor . '" font-family="Arial, sans-serif" font-size="' . $fontSize . '">';
        $svg .= $initials;
        $svg .= '</text>';
        $svg .= '</svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Obtenir le nom complet de l'utilisateur
     */
    public function getFullNameAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Obtenir le service de l'utilisateur selon son rôle
     */
    public function getServiceAttribute()
    {
        switch ($this->role) {
            case 'user':
                return $this->userSimple?->service;
            case 'tech':
                return $this->technicien?->service;
            default:
                return null;
        }
    }
}

