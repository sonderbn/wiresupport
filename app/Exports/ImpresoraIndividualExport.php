<?php
namespace App\Exports;

use App\Models\Equipo;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Events\AfterSheet;

class ImpresoraIndividualExport implements FromArray, WithEvents
{
    protected $impresora;

    public function __construct(Equipo $impresora)
    {
        $this->impresora = $impresora;
    }

    public function array(): array
    {
        return [
            ['FICHA TÉCNICA DE LA IMPRESORA'],
            ['Nombre de la Impresora:', $this->impresora->nombre_equipo],
            ['Sede:', $this->impresora->clinica->nombre ?? 'Sin sede'],
            ['Modelo:', $this->impresora->modelo],
            ['Número de Serie:', $this->impresora->numero_serie],
            ['Estado:', ucfirst($this->impresora->estado)],
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
