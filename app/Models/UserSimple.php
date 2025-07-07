<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSimple extends Model
{
    use HasFactory;

    protected $table = 'user_simples';
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = ['service_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_simple_id');
    }

}
