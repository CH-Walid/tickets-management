<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

     protected $fillable = [
        'titre',
       'is_official'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'categorie_id');
    }
}
