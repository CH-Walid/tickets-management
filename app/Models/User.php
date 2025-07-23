<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    public function getPhotoAttribute($value)
    {
        if (empty($value) || $value === 'avatars/default.png' || $value === 'avatars/default.jpg') {
            return null;
        }
        return $value;
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return asset('images/default-avatar.png'); // À créer dans public/images
    }


    use HasApiTokens, HasFactory, Notifiable;

    
    
    
    public function technicien()
    {
        return $this->hasOne(Technicien::class, 'id');
    }
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id');
    }
    public function chefTechnicien()
    {
        return $this->hasOne(ChefTechnicien::class, 'id');
    }
    public function userSimple()
    {
        return $this->hasOne(UserSimple::class, 'id');
    }

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
        'photo',
        'phone',
        'role',
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
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

}
