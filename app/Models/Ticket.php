<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Liste des champs pouvant être remplis en masse
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

    // 🔗 Relation avec l'utilisateur simple (créateur du ticket)
    public function userSimple()
    {
        return $this->belongsTo(UserSimple::class, 'user_simple_id');
    }

    // 🔗 Relation avec le technicien assigné
    public function technicien()
    {
        return $this->belongsTo(Technicien::class, 'technicien_id');
    }

    // 🔗 Relation avec la catégorie du ticket
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id'); 
    }

    // 🔗 Relation avec les commentaires du ticket
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
