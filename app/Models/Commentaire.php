<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;

     protected $fillable = ['content', 'technicien_id', 'ticket_id'];

    public function technicien()
    {
        return $this->belongsTo(Technicien::class, 'technicien_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
