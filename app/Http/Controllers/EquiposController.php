<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Clinica;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Exports\EquiposExport;
use App\Exports\EquipoIndividualExport;
use App\Exports\ImpresorasExport;
use App\Exports\ImpresoraIndividualExport;
use Maatwebsite\Excel\Facades\Excel;

class EquiposController extends Controller
{
    public function indexPC(Request $request)
    {
        $clinicas = Clinica::all();

        $query = Equipo::with('clinica')->where('tipo', 'PC');

        if ($request->filled('sede')) {
            $query->where('Id_clinica', $request->sede);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre_equipo', 'like', "%$buscar%")
                    ->orWhere('modelo', 'like', "%$buscar%")
                    ->orWhere('numero_serie', 'like', "%$buscar%");
            });
        }

        $equipos = $query->paginate(10);

        return view('equipos.index', compact('equipos', 'clinicas'));
    }

    public function indexImpresoras(Request $request)
    {
        $clinicas = Clinica::all();

        $query = Equipo::with('clinica')->where('tipo', 'Impresora');

        if ($request->filled('sede')) {
            $query->where('Id_clinica', $request->sede);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre_equipo', 'like', "%$buscar%")
                    ->orWhere('modelo', 'like', "%$buscar%")
                    ->orWhere('numero_serie', 'like', "%$buscar%");
            });
        }

        $impresoras = $query->paginate(10);

        return view('impresoras.index', compact('impresoras', 'clinicas'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre_equipo' => 'required',
            'tipo' => 'required',
            'estado' => 'required',
            'procesador' => 'nullable',
            'memoria_ram' => 'nullable',
            'disco_duro' => 'nullable',
            'modelo' => 'nullable',
            'numero_serie' => 'required|unique:equipos,numero_serie',
            'Id_clinica' => 'required|exists:clinicas,Id_clinica',
        ]);

        Equipo::create($request->all());

        return redirect()->back()->with('success', 'Equipo registrado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $equipo = Equipo::findOrFail($id);

        $request->validate([
            'nombre_equipo' => 'required',
            'tipo' => 'required',
            'estado' => 'required',
            'procesador' => 'nullable',
            'memoria_ram' => 'nullable',
            'disco_duro' => 'nullable',
            'modelo' => 'nullable',
            'numero_serie' => 'required|unique:equipos,numero_serie,' . $equipo->Id_equipo . ',Id_equipo',
            'Id_clinica' => 'required|exists:clinicas,Id_clinica',
        ]);


        $equipo->update($request->all());

        return redirect()->back()->with('success', 'Equipo actualizado correctamente.');
    }

    public function checkNumeroSerie(Request $request)
    {
        $numeroSerie = $request->query('numero_serie');
        $exists = Equipo::where('numero_serie', $numeroSerie)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function destroy($id)
    {
        Equipo::destroy($id);

        return redirect()->back()->with('success', 'Equipo eliminado correctamente.');
    }
    public function getEquiposEnReparacion($id)
    {
        $equipos = Equipo::where('Id_clinica', $id)
            ->where('estado', 'en reparacion')
            ->get();

        return response()->json(['equipos' => $equipos]);
    }
    public function historialReparaciones($id)
    {

        $equipo = Equipo::findOrFail($id);

        $ticketsTerminados = Ticket::where('id_equipo', $equipo->Id_equipo)
            ->where('estado', 'terminado')
            ->with(['clinica', 'usuario'])
            ->latest('fecha_resolucion')
            ->paginate(10);

        return view('tickets.historial_reparaciones', compact('ticketsTerminados', 'equipo'));
    }
    public function reportePdf(Request $request)
    {
        $query = Equipo::with('clinica')->where('tipo', 'PC');

        if ($request->filled('sede')) {
            $query->where('id_clinica', $request->sede);
        }

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre_equipo', 'like', '%' . $request->buscar . '%')
                    ->orWhere('modelo', 'like', '%' . $request->buscar . '%')
                    ->orWhere('numero_serie', 'like', '%' . $request->buscar . '%');
            });
        }

        $equipos = $query->get();
        $pdf = PDF::loadView('equipos.reporte_pdf', compact('equipos'));
        return $pdf->download('reporte_equipos.pdf');
    }

    public function reporteExcel(Request $request)
    {
        $sede = $request->input('sede');
        $buscar = $request->input('buscar');

        return Excel::download(new EquiposExport($sede, $buscar), 'reporte_equipos.xlsx');
    }


    public function reportePdfIndividual($id)
    {
        $equipo = Equipo::with('clinica')->findOrFail($id);
        $pdf = PDF::loadView('equipos.reporte_pdf_individual', compact('equipo'));
        return $pdf->download("reporte_equipo_{$equipo->numero_serie}.pdf");
    }

    public function exportarExcelIndividual($id)
    {
        $equipo = Equipo::with('clinica')->findOrFail($id);
        return Excel::download(new EquipoIndividualExport($equipo), "equipo_{$equipo->Id_equipo}.xlsx");
    }

    public function reportePdfImpresora(Request $request)
    {
        $query = Equipo::with('clinica')->where('tipo', 'Impresora');

        if ($request->filled('sede')) {
            $query->where('Id_clinica', $request->sede);
        }

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre_equipo', 'like', '%' . $request->buscar . '%')
                    ->orWhere('modelo', 'like', '%' . $request->buscar . '%')
                    ->orWhere('numero_serie', 'like', '%' . $request->buscar . '%');
            });
        }

        $impresoras = $query->get();
        $pdf = PDF::loadView('impresoras.reporte_pdf_impresoras', compact('impresoras'));
        return $pdf->download('reporte_impresoras.pdf');
    }

    public function reporteExcelImpresora(Request $request)
    {
        $sede = $request->input('sede');
        $buscar = $request->input('buscar');

        return Excel::download(new ImpresorasExport($sede, $buscar), 'reporte_impresoras.xlsx');
    }


    public function reportePdfIndividualImpresora($id)
    {
        $impresora = Equipo::with('clinica')->findOrFail($id);
        $pdf = PDF::loadView('impresoras.reporte_pdf_individual_impresora', compact('impresora'));
        return $pdf->download("reporte_impresora_{$impresora->numero_serie}.pdf");
    }

    public function exportarExcelIndividualImpresora($id)
    {
        $impresora = Equipo::with('clinica')->findOrFail($id);
        return Excel::download(new ImpresoraIndividualExport($impresora), "impresora_{$impresora->Id_equipo}.xlsx");
    }
}
