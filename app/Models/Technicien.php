<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\MassAssignmentException;


class Technicien extends Model
{
    use HasFactory;

    protected $table = 'techniciens';
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $incrementing = false;

    // we do not need code here since it's generated automatically using uuid
    protected $fillable = ['id', 'service_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'technicien_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'technicien_id');
    }



}
