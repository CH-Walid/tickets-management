<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admins';        
    public $timestamps = false;         
    protected $primaryKey = 'id';       
    public $incrementing = false;       

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
