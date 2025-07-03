<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $table = 'equipos';
    protected $primaryKey = 'Id_equipo';
    public $timestamps = false;

    protected $fillable = [
        'nombre_equipo',
        'tipo',
        'estado',
        'procesador',
        'memoria_ram',
        'disco_duro',
        'modelo',
        'numero_serie',
        'Id_clinica',
    ];

    public function clinica()
    {
        return $this->belongsTo(Clinica::class, 'Id_clinica');
    }
}
