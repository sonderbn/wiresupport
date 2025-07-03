<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clinica extends Model
{
    protected $table = 'clinicas';
    protected $primaryKey = 'Id_clinica';
    public $timestamps = false;

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'Id_clinica');
    }
}
