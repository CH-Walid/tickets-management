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
        return $this->hasMany(Technicien::class);
    }

    public function userSimples()
    {
        return $this->hasMany(UserSimple::class);
    }
    
}
