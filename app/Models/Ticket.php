<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'Id_tickets';
    public $timestamps = false;

    // Propiedad fillable para la asignación masiva
    protected $fillable = [
        'id_usuario',
        'id_equipo', // Cambiado a minúscula
        'id_clinica', // Cambiado a minúscula
        'fecha_creacion',
        'descripcion',
        'estado',
    ];


    // Relación con la clínica
    public function clinica()
    {
        return $this->belongsTo(Clinica::class, 'Id_clinica');
    }

    // Relación con el equipo
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'Id_equipo');
    }
    public function usuario()
{
    return $this->belongsTo(Usuarios::class, 'id_usuario'); // Asegúrate de que el campo sea correcto
}

}
