<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    // if we follow the Laravel convention, we can remove these lines
    // I mean by laravel convention, the table name is plural and the primary key is 'id'
    protected $table = 'admins';
    public $timestamps = false;

    // The primary key is 'id' by default, so we can remove this line
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
