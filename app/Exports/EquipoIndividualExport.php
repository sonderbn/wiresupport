<?php

namespace App\Exports;

use App\Models\Equipo;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Events\AfterSheet;

class EquipoIndividualExport implements FromArray, WithEvents
{
    protected $equipo;

    public function __construct(Equipo $equipo)
    {
        $this->equipo = $equipo;
    }

    public function array(): array
    {
        return [
            ['FICHA TÉCNICA DEL EQUIPO'],
            ['Nombre del Equipo:', $this->equipo->nombre_equipo],
            ['Sede:', $this->equipo->clinica->nombre ?? 'Sin sede'],
            ['Modelo:', $this->equipo->modelo],
            ['Número de Serie:', $this->equipo->numero_serie],
            ['Procesador:', $this->equipo->procesador],
            ['Memoria RAM:', $this->equipo->memoria_ram],
            ['Disco Duro:', $this->equipo->disco_duro],
            ['Tipo de Equipo:', ucfirst($this->equipo->tipo)],
            ['Estado:', ucfirst($this->equipo->estado)],
            ['Generado:', now()->format('d/m/Y H:i')],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $sheet->mergeCells('A1:B1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A')->getFont()->setBold(true);
                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
            },
        ];
    }
}

