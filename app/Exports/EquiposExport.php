<?php
namespace App\Exports;

use App\Models\Equipo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EquiposExport implements FromCollection, WithHeadings
{
    protected $sede;
    protected $busqueda;

    public function __construct($sede = null, $busqueda = null)
    {
        $this->sede = $sede;
        $this->busqueda = $busqueda;
    }

    public function collection()
    {
        $query = Equipo::with('clinica')->where('tipo', 'PC');
        if ($this->sede) {
            $query->where('id_clinica', $this->sede);
        }

        if ($this->busqueda) {
            $query->where(function ($q) {
                $q->where('nombre_equipo', 'like', '%' . $this->busqueda . '%')
                  ->orWhere('modelo', 'like', '%' . $this->busqueda . '%')
                  ->orWhere('numero_serie', 'like', '%' . $this->busqueda . '%');
            });
        }

        return $query->get()->map(function ($equipo) {
            return [
                $equipo->nombre_equipo,
                $equipo->clinica->nombre ?? 'Sin sede',
                $equipo->modelo,
                $equipo->numero_serie,
                $equipo->procesador,
                $equipo->memoria_ram,
                $equipo->disco_duro,
                $equipo->tipo,
                ucfirst($equipo->estado),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Sede',
            'Modelo',
            'Número de Serie',
            'Procesador',
            'RAM',
            'Disco Duro',
            'Tipo',
            'Estado',
        ];
    }
}
