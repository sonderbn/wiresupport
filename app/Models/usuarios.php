<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuarios extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombres',
        'apellidos',
        'usuario',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
    ];

    public function getAuthPassword(){
        return $this->password;
    }

    public $timestamps = false;
}
