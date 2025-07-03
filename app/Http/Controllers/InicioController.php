<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Equipo;
use App\Models\Usuarios;
use App\Models\Clinica;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;


class InicioController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:usuarios');
  }

  public function inicio()
  {
    $user = auth()->guard('usuarios')->user();

    if ($user->rol == 0) {
      $ticketsPendientes = Ticket::where('estado', 'pendiente')->count();
      $equiposRegistrados = Equipo::count();
      $usuariosRegistrados = Usuarios::count();

      $ticketsPorClinica = Ticket::select('id_clinica', DB::raw('count(*) as total'))
        ->where('estado', 'pendiente')
        ->groupBy('id_clinica')
        ->get()
        ->map(function ($item) {
          $nombreClinica = Clinica::find($item->id_clinica)?->nombre;
          return [
            'clinica' => $nombreClinica ?? 'Sin nombre',
            'total' => $item->total,
          ];
        });


      $equiposPorClinica = Equipo::select('id_clinica', DB::raw('count(*) as total'))
        ->groupBy('id_clinica')
        ->get()
        ->map(function ($item) {
          $nombreClinica = Clinica::find($item->id_clinica)?->nombre;
          return [
            'clinica' => $nombreClinica ?? 'Sin nombre',
            'total' => $item->total,
          ];
        });

      return view('menus.inicio', compact(
        'user',
        'ticketsPendientes',
        'equiposRegistrados',
        'usuariosRegistrados',
        'ticketsPorClinica',
        'equiposPorClinica'
      ));
    } elseif ($user->rol == 1) {
        $tickets = Ticket::with('equipo')
                         ->where('id_usuario', $user->id)
                         ->orderByDesc('fecha_creacion')
                         ->paginate(4);
        return view('menus.inicio_usuario', compact('user', 'tickets'));
    } else {
        abort(403, 'Acceso no autorizado');
    }
  }
}
