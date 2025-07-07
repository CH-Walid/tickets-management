<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    // we do not need to include created_at, deleted_at
    protected $fillable = [
        'titre',
        'description',
        'piece_jointe',
        'status',
        'priorite',
        'in_progress_at',
        'resolved_at',
        'closed_at',
        'user_simple_id',
        'technicien_id',
        'categorie_id'
    ];

    public function userSimple()
    {
        return $this->belongsTo(UserSimple::class, 'user_simple_id');
    }

    public function technicien()
    {
        return $this->belongsTo(Technicien::class, 'technicien_id');
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie');
    }
}
