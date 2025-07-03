<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\Clinica;
use App\Models\Equipo;


class TicketsController extends Controller
{


    public function index(Request $request)
    {
        $user = Auth::user();
        $clinicas = Clinica::all();

        if ($user->rol == 1) {

            $ticketsPendientes = Ticket::where('id_usuario', $user->id)
                ->where('estado', 'pendiente')
                ->with('equipo')
                ->latest('fecha_creacion')
                ->paginate(8);

            $ticketsTerminados = Ticket::where('id_usuario', $user->id)
                ->where('estado', 'terminado')
                ->with('equipo')
                ->latest('fecha_creacion')
                ->paginate(8);

            return view('tickets.usuario', compact(
                'ticketsPendientes',
                'ticketsTerminados',
                'user',
                'clinicas'
            ));
        }

        if ($user->rol == 0) {
            $ticketsPendientes = Ticket::with(['clinica', 'equipo', 'usuario'])
                ->where('estado', 'pendiente')
                ->when($request->id_clinica, function ($query) use ($request) {
                    return $query->where('Id_clinica', $request->id_clinica);
                })
                ->when($request->usuario_nombre, function ($query) use ($request) {
                    return $query->whereHas('usuario', function ($q) use ($request) {
                        $q->where('nombres', 'like', '%' . $request->usuario_nombre . '%');
                    });
                })
                ->when($request->tipo_equipo, function ($query) use ($request) {
                    return $query->whereHas('equipo', function ($q) use ($request) {
                        $q->where('tipo', $request->tipo_equipo);
                    });
                })
                ->when($request->descripcion, function ($query) use ($request) {
                    if ($request->descripcion === 'Otro') {
                        return $query->whereNotIn('descripcion', [
                            'La computadora no enciende',
                            'La pantalla está en negro',
                            'Problemas de conexión a Internet',
                            'El software no se abre',
                            'La impresora no imprime',
                            'Se acabó el toner',
                            'No hay tinta negra',
                            'No hay tinta azul',
                            'No hay tinta roja',
                            'No hay tinta amarilla',
                            'El teclado no responde',
                            'Problemas con el sistema operativo',
                            'El mouse no funciona',
                            'La computadora se reinicia sola'
                        ]);
                    } else {
                        return $query->where('descripcion', $request->descripcion);
                    }
                })
                ->when($request->fecha_creacion, function ($query) use ($request) {
                    return $query->whereDate('fecha_creacion', $request->fecha_creacion);
                })
                ->when($request->filtrar_recientes, function ($query) {
                    return $query->where('fecha_creacion', '>=', now()->subDay());
                })
                ->latest('fecha_creacion')
                ->paginate(8, ['*'], 'pendientes_page');

            $ticketsTerminados = Ticket::with(['clinica', 'equipo', 'usuario'])
                ->where('estado', 'terminado')
                ->when($request->id_clinica_terminado, function ($query) use ($request) {
                    return $query->where('Id_clinica', $request->id_clinica_terminado);
                })
                ->when($request->usuario_nombre_terminado, function ($query) use ($request) {
                    return $query->whereHas('usuario', function ($q) use ($request) {
                        $q->where('nombres', 'like', '%' . $request->usuario_nombre_terminado . '%');
                    });
                })
                ->when($request->tipo_equipo_terminado, function ($query) use ($request) {
                    return $query->whereHas('equipo', function ($q) use ($request) {
                        $q->where('tipo', $request->tipo_equipo_terminado);
                    });
                })
                ->when($request->descripcion_terminado, function ($query) use ($request) {
                    if ($request->descripcion_terminado === 'Otro') {
                        return $query->whereNotIn('descripcion', [
                            'La computadora no enciende',
                            'La pantalla está en negro',
                            'Problemas de conexión a Internet',
                            'El software no se abre',
                            'La impresora no imprime',
                            'Se acabó el toner',
                            'No hay tinta negra',
                            'No hay tinta azul',
                            'No hay tinta roja',
                            'No hay tinta amarilla',
                            'El teclado no responde',
                            'Problemas con el sistema operativo',
                            'El mouse no funciona',
                            'La computadora se reinicia sola'
                        ]);
                    } else {
                        return $query->where('descripcion', $request->descripcion_terminado);
                    }
                })
                ->when($request->fecha_resolucion, function ($query) use ($request) {
                    return $query->whereDate('fecha_resolucion', $request->fecha_resolucion);
                })
                ->when($request->filtrar_recientes_terminados, function ($query) {
                    return $query->where('fecha_resolucion', '>=', now()->subWeek());
                })
                ->latest('fecha_resolucion')
                ->paginate(8, ['*'], 'terminados_page');

            return view('tickets.tecnico', [
                'ticketsPendientes' => $ticketsPendientes,
                'ticketsTerminados' => $ticketsTerminados,
                'clinicas' => $clinicas,
            ]);
        }

        abort(403);
    }



    public function filtrarTickets(Request $request)
    {
        $estado = $request->input('estado');
        $clinicaId = $request->input('clinica_id');
        $usuarioNombre = $request->input('usuario_nombre');

        $query = Ticket::with(['clinica', 'equipo', 'usuario'])
            ->where('estado', $estado);

        if ($clinicaId) {
            $query->where('Id_clinica', $clinicaId);
        }

        if ($usuarioNombre) {
            $query->whereHas('usuario', function ($q) use ($usuarioNombre) {
                $q->where('nombres', 'like', '%' . $usuarioNombre . '%');
            });
        }

        $tickets = $query->latest('fecha_creacion')->get();

        return response()->json($tickets);
    }


    public function show($id)
    {
        $ticket = Ticket::with(['equipo', 'clinica'])->findOrFail($id);
        return response()->json($ticket);
    }
    public function showForTecnico($id)
    {
        \Log::info('Accediendo al ticket con ID: ' . $id . ' por el usuario técnico: ' . Auth::user()->id);
        $ticket = Ticket::with(['equipo', 'clinica', 'usuario'])->findOrFail($id);
        return response()->json($ticket);
    }
    public function getEquiposPorSede($id)
    {
        $equipos = Equipo::where('Id_clinica', $id)->get();
        return response()->json(['equipos' => $equipos]);
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_equipo' => 'required|exists:equipos,Id_equipo',
                'id_clinica' => 'required|exists:clinicas,Id_clinica',
                'descripcion' => 'required|string',
                'otroProblema' => 'nullable|string|max:255', // Validar el campo adicional
            ]);

            $descripcion = $request->descripcion === 'Otro' ? $request->otroProblema : $request->descripcion;

            $ticket = Ticket::create([
                'id_usuario' => Auth::guard('usuarios')->id(),
                'id_equipo' => $request->id_equipo,
                'id_clinica' => $request->id_clinica,
                'fecha_creacion' => now(),
                'descripcion' => $descripcion,
                'estado' => 'pendiente',
            ]);

            $equipo = Equipo::find($request->id_equipo);
            $equipo->estado = 'en reparacion';
            $equipo->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->estado == 'terminado') {
            return response()->json(['error' => 'No se puede editar un ticket terminado.'], 403);
        }

        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $ticket->descripcion = $request->descripcion;

        $ticket->save();

        return response()->json(['success' => true, 'message' => 'Ticket actualizado correctamente.']);
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->estado == 'terminado') {
            return response()->json(['error' => 'No se puede eliminar un ticket terminado.'], 403);
        }

        $ticket->delete();
        return response()->json(['success' => true]);
    }

    public function solucionar(Request $request, $id)
    {
        \DB::beginTransaction();
        try {
            $ticket = Ticket::with('equipo')->findOrFail($id);
            $ticket->estado = 'terminado';
            $ticket->fecha_resolucion = now();
            $ticket->solucion_descripcion = $request->solucion_descripcion;
            $ticket->save();

            if ($ticket->equipo) {
                $ticket->equipo->estado = 'activo';
                $ticket->equipo->reparaciones_count += 1;
                $ticket->equipo->save();
            }

            \DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error("Error al solucionar ticket: " . $e->getMessage());
            return response()->json(['error' => 'Error interno'], 500);
        }
    }
}
