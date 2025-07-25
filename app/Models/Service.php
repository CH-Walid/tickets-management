<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

     protected $fillable = ['titre'];

    public function techniciens()
    {
        return $this->hasMany(Technicien::class, 'service_id');
    }

    public function userSimples()
    {
        return $this->hasMany(UserSimple::class, 'service_id');
    }

    public function tickets()
    {
        return $this->hasManyThrough(
            Ticket::class,
            UserSimple::class,
            'service_id', // Foreign key on user_simples
            'user_simple_id', // Foreign key on tickets
            'id', // Local key on services
            'id' // Local key on user_simples
        );
    }

}
